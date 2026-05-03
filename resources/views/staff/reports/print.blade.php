<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSCTV Reports - {{ $from }} to {{ $to }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #1f2937;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            color: #6b7280;
            font-size: 14px;
        }

        .date-range {
            text-align: center;
            color: #6b7280;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .kpi-section {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .kpi-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .kpi-card.completed {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .kpi-card.pending {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .kpi-card.avg {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }

        .kpi-value {
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0;
        }

        .kpi-label {
            font-size: 12px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section {
            margin-bottom: 40px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead {
            background: #f3f4f6;
        }

        th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #d1d5db;
            font-size: 13px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }

        tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        tbody tr:hover {
            background: #f3f4f6;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-assigned {
            background: #dbeafe;
            color: #0c2d6b;
        }

        .status-in-progress {
            background: #e0e7ff;
            color: #312e81;
        }

        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        .chart-placeholder {
            background: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }

        .print-button {
            text-align: center;
            margin-bottom: 20px;
        }

        .print-button button {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .print-button button:hover {
            background: #4338ca;
        }

        @media print {
            body {
                background: white;
            }

            .container {
                max-width: 100%;
                padding: 20px;
            }

            .print-button {
                display: none;
            }

            .kpi-section {
                grid-template-columns: repeat(4, 1fr);
            }

            .two-column {
                grid-template-columns: 1fr 1fr;
            }

            .section {
                page-break-inside: avoid;
            }
        }

        @media (max-width: 768px) {
            .kpi-section {
                grid-template-columns: repeat(2, 1fr);
            }

            .two-column {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Print Button -->
        <div class="print-button">
            <button onclick="window.print()">🖨️ Print Report</button>
        </div>

        <!-- Header -->
        <div class="header">
            <h1>📊 PSCTV Reports Dashboard</h1>
            <p>Comprehensive Analytics and Performance Insights</p>
        </div>

        <!-- Date Range -->
        <div class="date-range">
            <strong>Report Period:</strong> {{ \Carbon\Carbon::parse($from)->format('F d, Y') }} to {{ \Carbon\Carbon::parse($to)->format('F d, Y') }}
        </div>

        <!-- KPI Cards -->
        <div class="kpi-section">
            <div class="kpi-card">
                <div class="kpi-label">Total Jobs</div>
                <div class="kpi-value">{{ $summary['total_jobs'] }}</div>
            </div>
            <div class="kpi-card completed">
                <div class="kpi-label">Completed</div>
                <div class="kpi-value">{{ $summary['completed'] }}</div>
            </div>
            <div class="kpi-card pending">
                <div class="kpi-label">Pending</div>
                <div class="kpi-value">{{ $summary['pending'] }}</div>
            </div>
            <div class="kpi-card avg">
                <div class="kpi-label">Avg Completion</div>
                <div class="kpi-value">{{ $summary['avg_completion'] }} <span style="font-size: 16px;">hrs</span></div>
            </div>
        </div>

        <!-- Technician Performance -->
        <div class="section">
            <h2 class="section-title">👨‍🔧 Technician Performance</h2>
            <table>
                <thead>
                    <tr>
                        <th>Technician Name</th>
                        <th style="text-align: right;">Jobs Completed</th>
                        <th style="text-align: right;">Avg Hours</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($technicians as $tech)
                        <tr>
                            <td>{{ $tech['name'] }}</td>
                            <td style="text-align: right;">{{ $tech['completed'] }}</td>
                            <td style="text-align: right;">{{ $tech['avg_hrs'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: #9ca3af;">No technician data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Job Types Distribution -->
        <div class="section">
            <h2 class="section-title">📋 Job Types Distribution</h2>
            <table>
                <thead>
                    <tr>
                        <th>Service Type</th>
                        <th style="text-align: right;">Total Jobs</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($job_types as $type)
                        <tr>
                            <td>{{ $type->service_type ?? 'N/A' }}</td>
                            <td style="text-align: right;">{{ $type->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="text-align: center; color: #9ca3af;">No job type data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Revenue Report -->
        <div class="section">
            <h2 class="section-title">💰 Revenue Report</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th style="text-align: right;">Total Revenue (₱)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($revenues as $revenue)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($revenue->date)->format('F d, Y') }}</td>
                            <td style="text-align: right;">₱ {{ number_format($revenue->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="text-align: center; color: #9ca3af;">No revenue data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Recent Service Requests -->
        <div class="section">
            <h2 class="section-title">⏱️ Recent Service Requests</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Technician</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Assigned</th>
                        <th>Completed</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_jobs as $job)
                        <tr>
                            <td>#{{ $job['id'] }}</td>
                            <td>{{ $job['customer'] }}</td>
                            <td>{{ $job['technician'] }}</td>
                            <td>{{ $job['type'] }}</td>
                            <td>
                                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $job['status'])) }}">
                                    {{ $job['status'] }}
                                </span>
                            </td>
                            <td>{{ $job['assigned_at'] ?? '—' }}</td>
                            <td>{{ $job['completed_at'] ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: #9ca3af;">No recent jobs found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
            <p>PSCTV Management System © {{ now()->year }}</p>
        </div>
    </div>
</body>
</html>
