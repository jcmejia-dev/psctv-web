<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Expired</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            margin: 20px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #ffc107;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #ffc107;
            margin-bottom: 10px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .subscription-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
        .service-status {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            margin: 10px 5px;
        }
        .button-renew {
            background-color: #007bff;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
            text-align: center;
        }
        .contact-info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .pricing {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Smart Cable Service Management</div>
            <h1 style="color: #856404; margin: 10px 0;">Subscription Expired</h1>
        </div>

        <div class="warning">
            <strong>⚠️ Your subscription has expired {{ $daysExpired }} days ago</strong>
        </div>

        @if($customer)
        <p>Dear {{ $customer->user->name ?? 'Valued Customer' }},</p>
        @else
        <p>Dear Valued Customer,</p>
        @endif

        <p>We hope you're doing well. We're writing to inform you that your cable service subscription has expired and requires immediate attention.</p>

        <div class="subscription-details">
            <h3 style="margin-top: 0; color: #495057;">Subscription Details</h3>
            @if($package)
            <p><strong>Package:</strong> {{ $package->name }}</p>
            <p><strong>Monthly Fee:</strong> ₱{{ number_format($package->price, 2) }}</p>
            @endif
            <p><strong>Subscription ID:</strong> {{ $subscription->id }}</p>
            <p><strong>Expiration Date:</strong> {{ \Carbon\Carbon::parse($subscription->end_date)->format('F j, Y') }}</p>
            <p><strong>Days Since Expiration:</strong> {{ $daysExpired }} days</p>
        </div>

        <div class="service-status">
            ⚠️ SERVICE STATUS: Your cable service has been temporarily disconnected due to non-payment.
        </div>

        <p>To restore your service immediately, you have the following options:</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="#" class="button button-renew">Renew Subscription Now</a>
            <a href="{{ route('packages.index') }}" class="button">View Available Packages</a>
        </div>

        @if($package)
        <div class="pricing">
            <h4 style="margin-top: 0;">Quick Renewal Pricing</h4>
            <p style="font-size: 18px; font-weight: bold; margin: 10px 0;">
                ₱{{ number_format($package->price, 2) }} per month
            </p>
            <p style="margin-bottom: 0;">No setup fees for existing customers!</p>
        </div>
        @endif

        <div class="contact-info">
            <h4 style="margin-top: 0; color: #495057;">Payment Options:</h4>
            <ul style="text-align: left;">
                <li>Online Payment Portal</li>
                <li>Bank Transfer</li>
                <li>Cash Payment at our office</li>
                <li>Mobile Payment (GCash/PayMaya)</li>
            </ul>
        </div>

        <p><strong>Important:</strong> Failure to renew your subscription may result in permanent account deactivation. Don't miss your favorite shows and channels - renew today!</p>

        <div class="contact-info">
            <h4 style="margin-top: 0; color: #495057;">Need Help?</h4>
            <p><strong>Phone:</strong> [Your Phone Number]<br>
            <strong>Email:</strong> support@smartcable.com<br>
            <strong>Office Hours:</strong> Monday - Friday, 8:00 AM - 5:00 PM</p>
        </div>

        <p>We value you as a customer and look forward to continuing to provide you with excellent cable service. Please don't hesitate to contact us if you have any questions.</p>

        <p>Best regards,<br>
        <strong>Smart Cable Service Management</strong><br>
        Customer Service Team</p>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.<br>
            © {{ date('Y') }} Smart Cable Service Management. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
