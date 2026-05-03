<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * Login endpoint
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user = Auth::user();

        // Check if user account is deactivated
        if ($user->isDeactivated()) {
            Auth::logout();
            return response()->json([
                'success' => false,
                'message' => 'Your account has been deactivated. Please contact support.',
            ], 403);
        }

        // Create Sanctum token for mobile use
        $token = $user->createToken('mobile')->plainTextToken;

        $customer = $user->customer;

        // Check if customer exists
        if (!$customer) {
            Auth::logout();
            return response()->json([
                'success' => false,
                'message' => 'Customer profile not found for this user.',
            ], 404);
        }

        // Load customer with relationships
        $customer->load([
            'subscriptions.package',
            'subscriptions.invoices' => function($query) {
                $query->whereIn('status', ['unpaid', 'partial', 'overdue']);
            }
        ]);

        $activeSubscription = $customer->activeSubscription();

        $subscriptionData = $activeSubscription ? [
            'id' => $activeSubscription->id,
            'plan' => $activeSubscription->package->name ?? null,
            'price' => $activeSubscription->package->price ?? null,
            'start_date' => $activeSubscription->start_date,
            'end_date' => $activeSubscription->end_date,
            'status' => $activeSubscription->status,
            'next_billing_date' => $activeSubscription->next_billing_date,
        ] : null;

        // Format subscriptions for response
        $subscriptions = $customer->subscriptions->map(function ($subscription) {
            return [
                'id' => $subscription->id,
                'package_id' => $subscription->package_id,
                'package_name' => $subscription->package->name ?? 'Unknown Package',
                'price' => (float) $subscription->package->price ?? 0,
                'billing_cycle' => $subscription->package->billing_cycle ?? 'monthly',
                'status' => $subscription->status,
                'start_date' => $subscription->start_date,
                'end_date' => $subscription->end_date,
                'next_billing_date' => $subscription->next_billing_date,
                'pending_invoices' => $subscription->invoices->map(function ($invoice) {
                    return [
                        'id' => $invoice->id,
                        'invoice_no' => $invoice->invoice_no,
                        'description' => $invoice->description,
                        'amount_due' => (float) $invoice->amount_due,
                        'amount_paid' => (float) $invoice->amount_paid,
                        'status' => $invoice->status,
                        'due_date' => $invoice->due_date,
                        'billing_date' => $invoice->billing_date,
                    ];
                }),
            ];
        });

        // Fetch all invoices from subscriptions
        $invoices = $customer->subscriptions()
            ->with('invoices')
            ->get()
            ->flatMap->invoices
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_no' => $invoice->invoice_no,
                    'description' => $invoice->description,
                    'amount_due' => (float) $invoice->amount_due,
                    'amount_paid' => (float) $invoice->amount_paid,
                    'status' => $invoice->status,
                    'due_date' => $invoice->due_date,
                    'billing_date' => $invoice->billing_date,
                    'subscription_id' => $invoice->subscription_id,
                ];
            });

        // ✅ FIXED: Get announcements for customer (simple version without is_active)
        $announcements = \App\Models\Announcement::where(function($query) {
                $query->where('audience', 'customers')
                      ->orWhere('audience', 'all');
            })
            ->where(function($query) {
                $query->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
            })
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->orderBy('created_at', 'desc')
            ->get(['id', 'title', 'content', 'priority', 'audience', 'created_at', 'start_date', 'end_date'])
            ->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'content' => $announcement->content,
                    'message' => $announcement->content, // alias for content
                    'priority' => $announcement->priority,
                    'audience' => $announcement->audience,
                    'created_at' => $announcement->created_at,
                    'start_date' => $announcement->start_date,
                    'end_date' => $announcement->end_date,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'customer' => [
                'id' => $customer->id,
                'user_id' => $customer->user_id,
                'account_number' => $customer->account_number,
                'phone' => $customer->phone,
                'status' => $customer->status,
                'address' => $customer->address,
                'city' => $customer->city,
                'province' => $customer->province,
                'zip_code' => $customer->zip_code,
                'active_subscription' => $subscriptionData,
                'subscriptions' => $subscriptions,
                'invoices' => $invoices,
                'announcements' => $announcements, // ✅ ADDED: Announcements
            ],
        ]);
    }

    /**
     * Fetch a single customer by user ID
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $customer = $user->customer;

        // Check if customer exists
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer profile not found for this user.',
            ], 404);
        }

        // Load customer with relationships
        $customer->load([
            'subscriptions.package',
            'subscriptions.invoices' => function($query) {
                $query->whereIn('status', ['unpaid', 'partial', 'overdue']);
            }
        ]);

        $activeSubscription = $customer->activeSubscription();
        $subscriptionData = $activeSubscription ? [
            'id' => $activeSubscription->id,
            'plan' => $activeSubscription->package->name ?? null,
            'price' => $activeSubscription->package->price ?? null,
            'start_date' => $activeSubscription->start_date,
            'end_date' => $activeSubscription->end_date,
            'status' => $activeSubscription->status,
            'next_billing_date' => $activeSubscription->next_billing_date,
        ] : null;

        // Format subscriptions for response
        $subscriptions = $customer->subscriptions->map(function ($subscription) {
            return [
                'id' => $subscription->id,
                'package_id' => $subscription->package_id,
                'package_name' => $subscription->package->name ?? 'Unknown Package',
                'price' => (float) $subscription->package->price ?? 0,
                'billing_cycle' => $subscription->package->billing_cycle ?? 'monthly',
                'status' => $subscription->status,
                'start_date' => $subscription->start_date,
                'end_date' => $subscription->end_date,
                'next_billing_date' => $subscription->next_billing_date,
                'pending_invoices' => $subscription->invoices->map(function ($invoice) {
                    return [
                        'id' => $invoice->id,
                        'invoice_no' => $invoice->invoice_no,
                        'description' => $invoice->description,
                        'amount_due' => (float) $invoice->amount_due,
                        'amount_paid' => (float) $invoice->amount_paid,
                        'status' => $invoice->status,
                        'due_date' => $invoice->due_date,
                        'billing_date' => $invoice->billing_date,
                    ];
                }),
            ];
        });

        $invoices = $customer->subscriptions()
            ->with('invoices')
            ->get()
            ->flatMap->invoices
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_no' => $invoice->invoice_no,
                    'description' => $invoice->description,
                    'amount_due' => (float) $invoice->amount_due,
                    'amount_paid' => (float) $invoice->amount_paid,
                    'status' => $invoice->status,
                    'due_date' => $invoice->due_date,
                    'billing_date' => $invoice->billing_date,
                    'subscription_id' => $invoice->subscription_id,
                ];
            });

        // ✅ FIXED: Get announcements for customer (same as login method)
        $announcements = \App\Models\Announcement::where(function($query) {
                $query->where('audience', 'customers')
                      ->orWhere('audience', 'all');
            })
            ->where(function($query) {
                $query->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
            })
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->orderBy('created_at', 'desc')
            ->get(['id', 'title', 'content', 'priority', 'audience', 'created_at', 'start_date', 'end_date'])
            ->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'content' => $announcement->content,
                    'message' => $announcement->content, // alias for content
                    'priority' => $announcement->priority,
                    'audience' => $announcement->audience,
                    'created_at' => $announcement->created_at,
                    'start_date' => $announcement->start_date,
                    'end_date' => $announcement->end_date,
                ];
            });

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'customer' => [
                'id' => $customer->id,
                'user_id' => $customer->user_id,
                'account_number' => $customer->account_number,
                'phone' => $customer->phone,
                'status' => $customer->status,
                'address' => $customer->address,
                'city' => $customer->city,
                'province' => $customer->province,
                'zip_code' => $customer->zip_code,
                'active_subscription' => $subscriptionData,
                'subscriptions' => $subscriptions,
                'invoices' => $invoices,
                'announcements' => $announcements, // ✅ ADDED: Announcements
            ],
        ]);
    }

    /**
     * Logout endpoint (revokes token)
     */
    public function logout(Request $request)
    {
        // Revoke the token used for the current request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }
}
