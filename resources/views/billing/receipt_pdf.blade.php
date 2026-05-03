<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt - {{ $bill->invoice_no }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1a1a1a;
            font-size: 13px;
            line-height: 1.5;
            background: #f8fafc;
            padding: 20px;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        
        /* Header Section */
        .receipt-header {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            color: white;
            padding: 30px 40px;
            position: relative;
            overflow: hidden;
        }
        
        .receipt-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            position: relative;
            z-index: 2;
        }
        
        .company-info {
            flex: 1;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .logo {
            height: 50px;
            width: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            backdrop-filter: blur(10px);
        }
        
        .logo-placeholder {
            color: white;
            font-weight: bold;
            font-size: 18px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .company-tagline {
            font-size: 13px;
            opacity: 0.9;
            font-weight: 400;
        }
        
        .receipt-meta {
            text-align: right;
        }
        
        .receipt-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }
        
        .invoice-number {
            font-size: 15px;
            opacity: 0.9;
            font-weight: 500;
        }
        
        .receipt-date {
            font-size: 13px;
            opacity: 0.8;
            margin-top: 5px;
        }
        
        /* Main Content */
        .receipt-content {
            padding: 40px;
        }
        
        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .info-card {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e2e8f0;
        }
        
        .info-card h3 {
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .customer-info p, .payment-info p {
            margin-bottom: 8px;
            font-size: 13px;
        }
        
        .customer-name {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 5px;
        }
        
        .payment-method {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            margin-top: 5px;
        }
        
        /* Invoice Table */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .invoice-table thead {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        }
        
        .invoice-table th {
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .invoice-table td {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .invoice-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .amount-column {
            text-align: right;
            font-weight: 600;
        }
        
        .description-cell {
            color: #475569;
        }
        
        .paid-amount {
            color: #059669;
            font-weight: 700;
        }
        
        .balance-amount {
            color: #dc2626;
            font-weight: 700;
        }
        
        .total-row {
            background: #f0fdf4;
            border-top: 2px solid #d1fae5;
        }
        
        .total-row td {
            font-weight: 700;
            color: #065f46;
            font-size: 14px;
        }
        
        /* Notes Section */
        .notes-section {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .notes-section h3 {
            font-size: 14px;
            font-weight: 600;
            color: #92400e;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .notes-section h3::before {
            content: '📝';
            margin-right: 8px;
        }
        
        .notes-content {
            color: #92400e;
            font-size: 13px;
            line-height: 1.6;
        }
        
        /* Footer */
        .receipt-footer {
            background: #f8fafc;
            padding: 25px 40px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .contact-info {
            text-align: left;
            font-size: 12px;
            color: #64748b;
        }
        
        .thank-you {
            font-weight: 600;
            color: #2563eb;
            font-size: 14px;
        }
        
        .watermark {
            opacity: 0.5;
            font-size: 11px;
            color: #64748b;
            margin-top: 15px;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            background: #d1fae5;
            color: #065f46;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }
        
        .status-badge::before {
            content: '✓';
            margin-right: 5px;
            font-weight: bold;
        }
        
        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .receipt-container {
                box-shadow: none;
                border: none;
                border-radius: 0;
            }
            
            .receipt-header {
                background: #2563eb !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header Section -->
        <div class="receipt-header">
            <div class="header-content">
                <div class="company-info">
                    <div class="logo-container">
                        <div class="logo">
                            <div class="logo-placeholder">SC</div>
                        </div>
                        <div>
                            <h1 class="company-name">Smart Cable Service</h1>
                            <p class="company-tagline">Premium Connectivity Solutions</p>
                        </div>
                    </div>
                </div>
                
                <div class="receipt-meta">
                    <h2 class="receipt-title">Payment Receipt</h2>
                    <p class="invoice-number">Invoice #{{ $bill->invoice_no }}</p>
                    <p class="receipt-date">Issued: {{ now()->format('F d, Y') }}</p>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="receipt-content">
            <!-- Customer & Payment Info -->
            <div class="info-grid">
                <div class="info-card customer-info">
                    <h3>Billed To</h3>
                    <p class="customer-name">{{ $bill->subscription?->customer?->user->name ?? $bill->customer_name ?? 'N/A' }}</p>
                    <p>{{ $bill->subscription->customer->user->email ?? 'N/A' }}</p>
                    <div class="status-badge">Payment Confirmed</div>
                </div>
                
                <div class="info-card payment-info">
                    <h3>Payment Details</h3>
                    @if($payment)
                        <p><strong>Method:</strong> <span class="payment-method">{{ $payment->method }}</span></p>
                        <p><strong>Reference:</strong> {{ $payment->reference ?? 'N/A' }}</p>
                        <p><strong>Paid On:</strong> {{ $payment->payment_date->format('M d, Y \\a\\t h:i A') }}</p>
                    @else
                        <p><i>No payment recorded</i></p>
                    @endif
                </div>
            </div>
            
            <!-- Invoice Table -->
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="amount-column">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="description-cell">{{ $bill->description }}</td>
                        <td class="amount-column">₱{{ number_format($bill->amount_due, 2) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Amount Paid</strong></td>
                        <td class="amount-column paid-amount">₱{{ number_format($bill->amount_paid, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td><strong>Balance Remaining</strong></td>
                        <td class="amount-column balance-amount">₱{{ number_format($bill->balanceRemaining(), 2) }}</td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Notes Section -->
            @if($notes)
            <div class="notes-section">
                <h3>Additional Notes</h3>
                <p class="notes-content">{{ $notes }}</p>
            </div>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="receipt-footer">
            <div class="footer-content">
                <div class="contact-info">
                    <p><strong>Smart Cable Service</strong></p>
                    <p>Customer Support: support@smartcable.com</p>
                    <p>Phone: +1 (555) 123-4567</p>
                </div>
                <div class="thank-you">
                    Thank you for your business!
                </div>
            </div>
            <div class="watermark">
                This is an official receipt. Please retain for your records.
            </div>
        </div>
    </div>
</body>
</html>