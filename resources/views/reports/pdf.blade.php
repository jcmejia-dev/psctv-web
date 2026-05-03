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
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: white;
        }

        .container {
            max-width: 100%;
            padding: 20px;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #1f2937;
            font-size: 22px;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 12px;
        }

        .date-range {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
            font-size: 11px;
            background: #f5f5f5;
            padding: 10px;
            border-radius: 4px;
        }

        .kpi-section {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .kpi-row {
            display: table-row;
        }

        .kpi-card {
            display: table-cell;
            width: 25%;
            background: #4f46e5;
            color: white;
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .kpi-card.completed {
            background: #10b981;
        }

        .kpi-card.pending {
            background: #f59e0b;
        }

        .kpi-card.avg {
            background: #3b82f6;
        }

        .kpi-value {
            font-size: 20px;
            font-weight: bold;
            margin: 5px 0;
        }

        .kpi-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.95;
        }

        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
        }

        thead {
            background: #f3f4f6;
        }

        th {
            padding: 8px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        td {
            padding: 6px 8px;
            border: 1px solid #e5e7eb;
        }

        tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        tbody tr:nth-child(odd) {
            background: white;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
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

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #666;
            font-size: 9px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .no-data {
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>PSCTV Reports Dashboard</h1>
            <p>Comprehensive Analytics and Performance Insights</p>
        </div>

        <!-- Date Range -->
        <div class="date-range">
            <strong>Report Period:</strong> {{ \Carbon\Carbon::parse($from)->format('F d, Y') }} to {{ \Carbon\Carbon::parse($to)->format('F d, Y') }}
        </div>

        @if(isset($summary))
        <!-- KPI Cards -->
        <div class="kpi-section">
            <div class="kpi-row">
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
                    <div class="kpi-value">{{ $summary['avg_completion'] }}<br><span style="font-size: 10px;">hrs</span></div>
                </div>
            </div>
        </div>
        @endif

        @if(isset($technicians))
        <!-- Technician Performance -->
        <div class="section">
            <h2 class="section-title">Technician Performance</h2>
            <table>
                <thead>
                    <tr>
                        <th>Technician Name</th>
                        <th class="text-right">Jobs Completed</th>
                        <th class="text-right">Avg Hours</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($technicians as $tech)
                        <tr>
                            <td>{{ $tech['name'] }}</td>
                            <td class="text-right">{{ $tech['completed'] }}</td>
                            <td class="text-right">{{ $tech['avg_hrs'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="no-data">No technician data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif

        @if(isset($job_types))
        <!-- Job Types Distribution -->
        <div class="section">
            <h2 class="section-title">Job Types Distribution</h2>
            <table>
                <thead>
                    <tr>
                        <th>Service Type</th>
                        <th class="text-right">Total Jobs</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($job_types as $type)
                        <tr>
                            <td>{{ $type->service_type ?? 'N/A' }}</td>
                            <td class="text-right">{{ $type->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="no-data">No job type data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif

        @if(isset($revenues))
        <!-- Revenue Report -->
        <div class="section">
            <h2 class="section-title">Revenue Report</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th class="text-right">Total Revenue (₱)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($revenues as $revenue)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($revenue->date)->format('F d, Y') }}</td>
                            <td class="text-right">₱ {{ number_format($revenue->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="no-data">No revenue data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif

        <!-- Recent Service Requests -->
        <div class="section">
            <h2 class="section-title">Recent Service Requests</h2>
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
                            <td style="font-size: 8px;">{{ $job['assigned_at'] ?? '—' }}</td>
                            <td style="font-size: 8px;">{{ $job['completed_at'] ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="no-data">No recent jobs found</td>
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
