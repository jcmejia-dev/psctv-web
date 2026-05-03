<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'service' => 'required|string|in:internet,cable,phone,bundle,support',
            'message' => 'required|string|max:1000',
            'g-recaptcha-response' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify reCAPTCHA
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $recaptchaSecret = config('services.recaptcha.secret');

        $recaptchaVerify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
        $recaptchaData = json_decode($recaptchaVerify);

        if (!$recaptchaData->success) {
            return response()->json([
                'success' => false,
                'message' => 'reCAPTCHA verification failed'
            ], 422);
        }

        try {
            // Send email to PSCTV
            Mail::to('psctvincacctg@gmail.com')->send(new ContactFormMail($request->all()));

            // Send confirmation email to customer
            Mail::to($request->email)->send(new ContactFormMail($request->all(), true));

            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your message has been sent successfully. We will contact you soon.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error sending your message. Please try again.'
            ], 500);
        }
    }
}
