<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $bill->invoice_no }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; }
        .receipt { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; border-bottom: 2px solid #007bff; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { max-width: 150px; margin-bottom: 10px; }
        .company-info { margin-bottom: 20px; }
        .bill-details { display: table; width: 100%; margin-bottom: 30px; }
        .bill-details div { display: table-cell; width: 50%; vertical-align: top; }
        .bill-details h3 { margin-top: 0; color: #007bff; }
        .amount { font-size: 24px; font-weight: bold; color: #28a745; text-align: center; margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 5px; }
        .payment-info { margin-top: 30px; }
        .payment-info table { width: 100%; border-collapse: collapse; }
        .payment-info th, .payment-info td { padding: 10px; text-align: left; border-bottom: 1px solid #dee2e6; }
        .payment-info th { background-color: #f8f9fa; font-weight: bold; }
        .footer { margin-top: 40px; text-align: center; color: #6c757d; font-size: 14px; border-top: 1px solid #dee2e6; padding-top: 20px; }
        .notes { margin-top: 20px; padding: 15px; background: #e9ecef; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <div class="company-info">
                <h1>Payment Receipt</h1>
                <p>PSCTV System</p>
                <p>Thank you for your payment!</p>
            </div>
        </div>

        <div class="bill-details">
            <div>
                <h3>Bill Information</h3>
                <p><strong>Invoice No:</strong> {{ $bill->invoice_no }}</p>
                <p><strong>Bill Date:</strong> {{ $bill->created_at->format('M d, Y') }}</p>
                <p><strong>Due Date:</strong> {{ $bill->due_date ? $bill->due_date->format('M d, Y') : 'N/A' }}</p>
            </div>
            <div>
                <h3>Customer Information</h3>
                <p><strong>Name:</strong> {{ $bill->subscription->customer->user->name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $bill->subscription->customer->user->email ?? 'N/A' }}</p>
                <p><strong>Subscription:</strong> {{ $bill->subscription->package->name ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="amount">
            Amount Paid: ₱{{ number_format($payment->amount_paid, 2) }}
        </div>

        <div class="payment-info">
            <h3>Payment Details</h3>
            <table>
                <tr>
                    <th>Payment Date:</th>
                    <td>{{ $payment->payment_date->format('M d, Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Payment Method:</th>
                    <td>{{ $payment->method ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Reference:</th>
                    <td>{{ $payment->reference ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td>{{ ucfirst($payment->status) }}</td>
                </tr>
            </table>
        </div>

        @if($notes)
            <div class="notes">
                <strong>Notes:</strong><br>
                {{ $notes }}
            </div>
        @endif

        <div class="footer">
            <p>This is a computer-generated receipt. No signature required.</p>
            <p>For any inquiries, please contact our support team.</p>
        </div>
    </div>
</body>
</html>
