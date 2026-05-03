<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Get system status
        $systemStatus = $this->getSystemStatus();

        // Get user preferences
        $preferences = [
            'theme' => session('theme', 'light'),
            'notifications' => $user->notification_preferences ?? ['email' => true, 'sms' => false, 'push' => true],
            'language' => $user->language ?? 'en',
        ];

        return view('settings.index', compact('user', 'systemStatus', 'preferences'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark,system',
            'notifications.email' => 'boolean',
            'notifications.sms' => 'boolean',
            'notifications.push' => 'boolean',
            'language' => 'required|in:en,es,fr,tl',
        ]);

        $user = Auth::user();

        // Update user preferences
        $user->update([
            'notification_preferences' => $request->input('notifications', ['email' => true, 'sms' => false, 'push' => true]),
            'language' => $request->language,
        ]);

        // Store theme preference in session
        session(['theme' => $request->theme]);

        return back()->with('success', 'Settings updated successfully!');
    }

    public function systemStatus()
    {
        return response()->json($this->getSystemStatus());
    }

    private function getSystemStatus()
    {
        $status = [
            'database' => $this->checkDatabaseStatus(),
            'cache' => $this->checkCacheStatus(),
            'queue' => $this->checkQueueStatus(),
            'timestamp' => now()->toISOString(),
        ];

        // Determine overall status
        $statuses = [$status['database']['status'], $status['cache']['status'], $status['queue']['status']];
        if ($statuses === ['healthy', 'healthy', 'healthy']) {
            $status['overall'] = 'healthy';
            $status['overall_text'] = 'All Systems Operational';
        } elseif (in_array('unhealthy', $statuses)) {
            $status['overall'] = 'unhealthy';
            $status['overall_text'] = 'System Issues Detected';
        } else {
            $status['overall'] = 'warning';
            $status['overall_text'] = 'Some Systems Degraded';
        }

        return $status;
    }

    private function checkDatabaseStatus()
    {
        try {
            $start = microtime(true);
            DB::select('SELECT 1');
            $responseTime = round((microtime(true) - $start) * 1000, 2);

            return [
                'status' => 'healthy',
                'response_time' => $responseTime,
                'message' => 'Database connection is healthy'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'response_time' => null,
                'message' => 'Database connection failed: ' . $e->getMessage()
            ];
        }
    }

    private function checkCacheStatus()
    {
        try {
            $start = microtime(true);
            $store = Cache::store();
            $driver = $store->getStore();

            // Different checks based on cache driver
            if ($driver instanceof \Illuminate\Cache\DatabaseStore) {
                // For database cache, try to set and get a test value
                $testKey = 'cache_test_' . time();
                $store->put($testKey, 'test', 10); // 10 seconds
                $value = $store->get($testKey);
                $store->forget($testKey);
                if ($value !== 'test') {
                    throw new \Exception('Database cache read/write test failed');
                }
            } elseif (method_exists($driver, 'connection') && method_exists($driver->connection(), 'ping')) {
                // For Redis/Memcached, use ping
                $driver->connection()->ping();
            } elseif ($driver instanceof \Illuminate\Cache\FileStore) {
                // For file cache, check if directory is writable
                $path = $store->getDirectory();
                if (!is_writable($path)) {
                    throw new \Exception('Cache directory is not writable');
                }
                // Try a simple put/get
                $testKey = 'cache_test_' . time();
                $store->put($testKey, 'test', 10);
                $value = $store->get($testKey);
                $store->forget($testKey);
                if ($value !== 'test') {
                    throw new \Exception('File cache read/write test failed');
                }
            } elseif ($driver instanceof \Illuminate\Cache\ArrayStore) {
                // Array cache is always healthy (in-memory)
            } else {
                // For other drivers, try a simple put/get test
                $testKey = 'cache_test_' . time();
                $store->put($testKey, 'test', 10);
                $value = $store->get($testKey);
                $store->forget($testKey);
                if ($value !== 'test') {
                    throw new \Exception('Cache read/write test failed');
                }
            }

            $responseTime = round((microtime(true) - $start) * 1000, 2);

            return [
                'status' => 'healthy',
                'response_time' => $responseTime,
                'message' => 'Cache is operational'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'response_time' => null,
                'message' => 'Cache check failed: ' . $e->getMessage()
            ];
        }
    }

    private function checkQueueStatus()
    {
        try {
            $start = microtime(true);
            // Check if queue is working by checking failed jobs count
            $failedJobs = DB::table('failed_jobs')->count();
            $responseTime = round((microtime(true) - $start) * 1000, 2);

            return [
                'status' => 'healthy',
                'response_time' => $responseTime,
                'message' => 'Queue system is operational',
                'failed_jobs' => $failedJobs
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'response_time' => null,
                'message' => 'Queue system check failed: ' . $e->getMessage()
            ];
        }
    }
}
