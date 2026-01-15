<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizations Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            font-size: 10px;
            line-height: 1.3;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px solid #333;
            padding-bottom: 8px;
        }

        .header h1 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        .header h2 {
            margin: 3px 0;
            font-size: 12px;
            color: #666;
        }

        .header .address {
            margin: 5px 0;
            font-size: 9px;
            color: #777;
        }
        
        .report-title-section {
            text-align: center;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 8px;
            border-radius: 5px;
        }

        .report-main-title {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }

        .report-subtitle {
            font-size: 9px;
            margin-top: 2px;
            opacity: 0.9;
        }

        .report-info {
            margin-bottom: 8px;
            background-color: #f8f9fa;
            padding: 6px;
            border-radius: 3px;
            font-size: 8px;
        }

        .report-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-info td {
            padding: 2px;
            border: none;
        }
        
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 10px;
            padding: 0;
        }

        .stat-item {
            text-align: center;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 10px 8px;
            border-radius: 5px;
        }

        .stat-number {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2px;
            display: block;
        }

        .stat-label {
            font-size: 7px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .organizations-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 8px;
        }

        .organizations-table th,
        .organizations-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        .organizations-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            font-size: 8px;
        }

        .organizations-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .organizations-table tr:hover {
            background-color: #f5f5f5;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-active { background-color: #d4edda; color: #155724; }
        .status-suspended { background-color: #f8d7da; color: #721c24; }
        .status-pending { background-color: #fff3cd; color: #856404; }

        .department-section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .department-header {
            background: linear-gradient(135deg, #6f42c1, #5a2d91);
            color: white;
            padding: 8px 12px;
            margin-bottom: 10px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 11px;
        }

        .footer {
            position: fixed;
            bottom: 10px;
            left: 15px;
            right: 15px;
            text-align: center;
            font-size: 7px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>OFFICE OF STUDENT AFFAIRS</h1>
        <h2>{{ $adminRole }}</h2>
        <div class="address">
            St. Paul University Philippines<br>
            Tuguegarao City, 3500
        </div>
    </div>

    <!-- Report Title Section -->
    <div class="report-title-section">
        <div class="report-main-title">Organizations Comprehensive Report</div>
        <div class="report-subtitle">Complete Overview of All Registered Organizations</div>
    </div>

    <!-- Report Information -->
    <div class="report-info">
        <table>
            <tr>
                <td><strong>Report Type:</strong></td>
                <td>Organizations Overview Report</td>
                <td><strong>Generated Date:</strong></td>
                <td>{{ $generatedDate }}</td>
            </tr>
            <tr>
                <td><strong>Generated By:</strong></td>
                <td>{{ $adminName }}</td>
                <td><strong>Generated Time:</strong></td>
                <td>{{ $generatedTime }}</td>
            </tr>
            <tr>
                <td><strong>Total Organizations:</strong></td>
                <td>{{ $totalClubs }}</td>
                <td><strong>Report Status:</strong></td>
                <td>Official</td>
            </tr>
        </table>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $totalClubs }}</div>
            <div class="stat-label">Total Organizations</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $activeClubs }}</div>
            <div class="stat-label">Active Organizations</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $suspendedClubs }}</div>
            <div class="stat-label">Suspended Organizations</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $clubs->sum('member_count') }}</div>
            <div class="stat-label">Total Members</div>
        </div>
    </div>

    <!-- Complete Organizations Table -->
    <div class="organizations-section">
        <table class="organizations-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 25%;">Organization Name</th>
                    <th style="width: 12%;">Type</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 12%;">Date Registered</th>
                    <th style="width: 8%;">Members</th>
                    <th style="width: 8%;">Officers</th>
                    <th style="width: 20%;">Adviser</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clubs as $index => $club)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td><strong>{{ $club->name }}</strong><br><small style="color: #666;">{{ $club->department }}</small></td>
                        <td>{{ $club->club_type }}</td>
                        <td>
                            <span class="status-badge status-{{ $club->status }}">
                                {{ ucfirst(str_replace('_', ' ', $club->status)) }}
                            </span>
                        </td>
                        <td>{{ $club->date_registered ? $club->date_registered->format('M j, Y') : 'N/A' }}</td>
                        <td style="text-align: center;">{{ $club->member_count }}</td>
                        <td style="text-align: center;">{{ $club->officers->count() }}</td>
                        <td>{{ $club->adviser_name ?: 'Not Assigned' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div>
            <strong>Club Management System</strong> - St. Paul University Philippines<br>
            Generated by Office of Student Affairs • All Rights Reserved © {{ date('Y') }}<br>
            For inquiries, contact: {{ $adminName }} ({{ $adminRole }})
        </div>
    </div>
</body>
</html>
