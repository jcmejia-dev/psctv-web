<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Suspended - Payment Overdue</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 20px; text-align: center; border-radius: 5px; margin-bottom: 20px; }
        .content { background-color: #ffffff; padding: 20px; border: 1px solid #dee2e6; border-radius: 5px; }
        .warning { background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #dee2e6; font-size: 12px; color: #6c757d; }
        .button { display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #dc3545; margin: 0;">⚠️ Service Suspended</h1>
            <p style="margin: 10px 0 0 0;">Your cable service has been temporarily suspended</p>
        </div>

        <div class="content">
            <p>Dear {{ $user->name }},</p>

            <div class="warning">
                <strong>⚠️ SERVICE STATUS: Your cable service has been temporarily suspended due to non-payment.</strong>
            </div>

            <p>We regret to inform you that your cable service subscription has been suspended because your payment is overdue. This suspension is part of our billing policy to ensure fair service for all customers.</p>

            <h3>Subscription Details:</h3>
            <ul>
                <li><strong>Package:</strong> {{ $package->name }}</li>
                <li><strong>Subscription ID:</strong> #{{ $subscription->id }}</li>
                <li><strong>Monthly Fee:</strong> ₱{{ number_format($package->price, 2) }}</li>
            </ul>

            <h3>What happens next?</h3>
            <ol>
                <li><strong>Immediate Action Required:</strong> Please settle your outstanding payment as soon as possible.</li>
                <li><strong>Service Restoration:</strong> Once payment is received, your service will be restored within 24 hours.</li>
                <li><strong>Reconnection Fee:</strong> A ₱{{ $package->reconnection_fee ?? 50 }} reconnection fee may apply.</li>
                <li><strong>Complete Disconnection:</strong> If payment is not received within 3 days of suspension, your service may be permanently disconnected.</li>
            </ol>

            <h3>Payment Options:</h3>
            <ul>
                <li>Online Payment Portal</li>
                <li>Bank Transfer</li>
                <li>Cash Payment at our office</li>
                <li>Mobile Payment (GCash, PayMaya)</li>
            </ul>

            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/billing') }}" class="button">Pay Now Online</a>
            </p>

            <p>If you have already made a payment and believe this suspension was made in error, please contact our customer service immediately.</p>

            <p><strong>Customer Service:</strong><br>
            Phone: (02) 123-4567<br>
            Email: support@psctv.com<br>
            Office Hours: Monday-Saturday, 8:00 AM - 6:00 PM</p>

            <p>We appreciate your prompt attention to this matter and look forward to restoring your service.</p>

            <p>Best regards,<br>
            <strong>PSCTV Billing Department</strong></p>
        </div>

        <div class="footer">
            <p>This is an automated message from PSCTV Billing System. Please do not reply to this email.</p>
            <p>© {{ date('Y') }} PSCTV. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
