<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overdue Payment Notice</title>
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
            border-bottom: 3px solid #dc3545;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 10px;
        }
        .alert {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .invoice-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
            text-align: center;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #dc3545;
            color: white;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Smart Cable Service Management</div>
            <h1 style="color: #dc3545; margin: 10px 0;">Payment Overdue Notice</h1>
        </div>

        <div class="alert">
            <strong>⚠️ URGENT: Your payment is {{ $daysOverdue }} days overdue</strong>
        </div>

        @if($customer)
        <p>Dear {{ $customer->user->name ?? 'Valued Customer' }},</p>
        @else
        <p>Dear Valued Customer,</p>
        @endif

        <p>We hope this email finds you well. We're writing to inform you that your payment for the following invoice is currently overdue:</p>

        <div class="invoice-details">
            <h3 style="margin-top: 0; color: #495057;">Invoice Details</h3>
            <p><strong>Invoice Number:</strong> {{ $invoice->invoice_no }}</p>
            <p><strong>Description:</strong> {{ $invoice->description }}</p>
            <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('F j, Y') }}</p>
            <p><strong>Days Overdue:</strong> {{ $daysOverdue }} days</p>
        </div>

        <div class="amount">
            Amount Due: ₱{{ number_format($invoice->amount_due, 2) }}
        </div>

        <p>Your service may be affected if payment is not received promptly. To avoid service interruption, please make payment as soon as possible using one of the following methods:</p>

        <div class="contact-info">
            <h4 style="margin-top: 0; color: #495057;">Payment Options:</h4>
            <ul style="text-align: left;">
                <li>Online Payment Portal</li>
                <li>Bank Transfer</li>
                <li>Cash Payment at our office</li>
                <li>Mobile Payment (GCash/PayMaya)</li>
            </ul>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('billing.show', $invoice->id) }}" class="button">View Invoice & Pay Now</a>
        </div>

        <p>If you have already made payment, please disregard this notice. For any questions or concerns, please don't hesitate to contact our billing department.</p>

        <div class="contact-info">
            <h4 style="margin-top: 0; color: #495057;">Contact Information:</h4>
            <p><strong>Phone:</strong> [Your Phone Number]<br>
            <strong>Email:</strong> billing@smartcable.com<br>
            <strong>Office Hours:</strong> Monday - Friday, 8:00 AM - 5:00 PM</p>
        </div>

        <p>Thank you for your prompt attention to this matter. We appreciate your business and look forward to continuing to serve you.</p>

        <p>Best regards,<br>
        <strong>Smart Cable Service Management</strong><br>
        Billing Department</p>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.<br>
            © {{ date('Y') }} Smart Cable Service Management. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
