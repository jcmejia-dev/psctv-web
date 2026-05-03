<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Disconnected - Payment Overdue</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8d7da; padding: 20px; text-align: center; border-radius: 5px; margin-bottom: 20px; }
        .content { background-color: #ffffff; padding: 20px; border: 1px solid #dee2e6; border-radius: 5px; }
        .alert { background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #dee2e6; font-size: 12px; color: #6c757d; }
        .button { display: inline-block; padding: 10px 20px; background-color: #dc3545; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .urgent { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #dc3545; margin: 0;">❌ Service Disconnected</h1>
            <p style="margin: 10px 0 0 0;">Your cable service has been permanently disconnected</p>
        </div>

        <div class="content">
            <p>Dear {{ $user->name }},</p>

            <div class="alert">
                <strong class="urgent">🚫 SERVICE STATUS: Your cable service has been permanently disconnected due to non-payment.</strong>
            </div>

            <p>We regret to inform you that your cable service subscription has been permanently disconnected because your payment remains overdue after an extended period. This action is taken as a last resort in accordance with our billing policy.</p>

            <h3>Subscription Details:</h3>
            <ul>
                <li><strong>Package:</strong> {{ $package->name }}</li>
                <li><strong>Subscription ID:</strong> #{{ $subscription->id }}</li>
                <li><strong>Monthly Fee:</strong> ₱{{ number_format($package->price, 2) }}</li>
            </ul>

            <h3 class="urgent">⚠️ IMPORTANT: Service Restoration Process</h3>
            <p>To restore your service, you must:</p>
            <ol>
                <li><strong>Clear All Outstanding Balances:</strong> Pay all overdue amounts plus any applicable fees.</li>
                <li><strong>Pay Reconnection Fee:</strong> ₱{{ $package->reconnection_fee ?? 50 }} reconnection fee is required.</li>
                <li><strong>Contact Customer Service:</strong> Visit our office or call to initiate reconnection.</li>
                <li><strong>Schedule Technician Visit:</strong> A technician will need to restore your service connection.</li>
            </ol>

            <h3>Outstanding Balance:</h3>
            <p>Please check your account for the complete breakdown of outstanding payments. You can view your billing history in your customer portal.</p>

            <h3>Payment Options:</h3>
            <ul>
                <li>Online Payment Portal</li>
                <li>Bank Transfer</li>
                <li>Cash Payment at our office</li>
                <li>Mobile Payment (GCash, PayMaya)</li>
            </ul>

            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/billing') }}" class="button">View Outstanding Bills</a>
            </p>

            <div class="alert">
                <strong>⚠️ Note:</strong> Service will not be automatically restored. You must contact customer service and complete the reconnection process.
            </div>

            <p><strong>Customer Service:</strong><br>
            Phone: (02) 123-4567<br>
            Email: support@psctv.com<br>
            Office Hours: Monday-Saturday, 8:00 AM - 6:00 PM<br>
            Address: [Your Office Address]</p>

            <p>We understand this may be inconvenient, but maintaining payment is essential for continued service. We hope to restore your service soon.</p>

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
