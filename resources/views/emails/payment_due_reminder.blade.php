<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Due Reminder</title>
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
        .alert {
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
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #ffc107;
            text-align: center;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #ffc107;
            color: #212529;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
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
        .urgent {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Smart Cable Service Management</div>
            <h1 style="color: #ffc107; margin: 10px 0;">Payment Due Reminder</h1>
        </div>

        @if($daysUntilDue > 0)
        <div class="alert">
            <strong>⏰ REMINDER: Your payment is due in {{ $daysUntilDue }} day{{ $daysUntilDue > 1 ? 's' : '' }}</strong>
        </div>
        @else
        <div class="urgent">
            <strong>⚠️ URGENT: Your payment is due TODAY</strong>
        </div>
        @endif

        @if($customer)
        <p>Dear {{ $customer->user->name ?? 'Valued Customer' }},</p>
        @else
        <p>Dear Valued Customer,</p>
        @endif

        <p>This is a friendly reminder that your subscription payment will be due {{ $daysUntilDue > 0 ? 'in ' . $daysUntilDue . ' day' . ($daysUntilDue > 1 ? 's' : '') : 'today' }}.</p>

        <div class="subscription-details">
            <h3 style="margin-top: 0; color: #495057;">Subscription Details</h3>
            <p><strong>Account Number:</strong> {{ $customer->account_number ?? 'N/A' }}</p>
            <p><strong>Package:</strong> {{ $package->name ?? 'N/A' }}</p>
            <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($subscription->end_date)->format('F j, Y') }}</p>
            @if($daysUntilDue > 0)
            <p><strong>Days Until Due:</strong> {{ $daysUntilDue }} days</p>
            @else
            <p><strong>Status:</strong> <span style="color: #dc3545; font-weight: bold;">DUE TODAY</span></p>
            @endif
        </div>

        <div class="amount">
            Amount Due: ₱{{ number_format($package->price ?? 0, 2) }}
        </div>

        <p>Please ensure your payment is made on time to avoid any service interruptions. You can make your payment through any of the following methods:</p>

        <div class="contact-info">
            <h4 style="margin-top: 0; color: #495057;">Payment Options:</h4>
            <ul style="text-align: left;">
                <li>Online Payment Portal</li>
                <li>Bank Transfer</li>
                <li>Cash Payment at our office</li>
                <li>Mobile Payment (GCash/PayMaya)</li>
            </ul>
        </div>

        @if($daysUntilDue <= 0)
        <div class="urgent">
            <strong>Important:</strong> Failure to make payment today may result in service suspension or disconnection. Please make your payment immediately to avoid any inconvenience.
        </div>
        @endif

        <div style="text-align: center;">
            <a href="{{ route('billing.index') }}" class="button">Make Payment Now</a>
        </div>

        <p>If you have already made payment, please disregard this reminder. For any questions or concerns, please don't hesitate to contact our billing department.</p>

        <div class="contact-info">
            <h4 style="margin-top: 0; color: #495057;">Contact Information:</h4>
            <p><strong>Phone:</strong> [Your Phone Number]<br>
            <strong>Email:</strong> billing@smartcable.com<br>
            <strong>Office Hours:</strong> Monday - Friday, 8:00 AM - 5:00 PM</p>
        </div>

        <p>Thank you for your continued business. We appreciate your prompt attention to this matter.</p>

        <p>Best regards,<br>
        <strong>Smart Cable Service Management</strong><br>
        Billing Department</p>

        <div class="footer">
            <p>This is an automated reminder. Please do not reply to this email.<br>
            © {{ date('Y') }} Smart Cable Service Management. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
