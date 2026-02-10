<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Report - {{ $timePeriodLabel }}</title>
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
            margin-bottom: 15px;
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
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
            margin-bottom: 12px;
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            color: white;
            padding: 10px;
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
            margin-bottom: 10px;
            background-color: #f8f9fa;
            padding: 8px;
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
            margin-bottom: 15px;
            padding: 0;
        }

        .stat-item {
            text-align: center;
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            color: white;
            padding: 12px 8px;
            border-radius: 5px;
        }

        .stat-number {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
            display: block;
        }

        .stat-label {
            font-size: 8px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .activity-section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .section-header {
            background: linear-gradient(135deg, #6f42c1, #5a2d91);
            color: white;
            padding: 8px 12px;
            margin-bottom: 10px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 11px;
        }
        
        .activity-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .activity-table th,
        .activity-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 8px;
        }

        .activity-table th {
            background-color: #6f42c1;
            color: white;
            font-weight: bold;
        }

        .activity-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-approved { background-color: #d4edda; color: #155724; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }
        .status-active { background-color: #d4edda; color: #155724; }
        .status-suspended { background-color: #f8d7da; color: #721c24; }
        .status-draft { background-color: #e2e3e5; color: #383d41; }

        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px dashed #ddd;
            font-size: 9px;
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
        <div class="report-main-title">System Activity Report</div>
        <div class="report-subtitle">{{ $timePeriodLabel }} Activity Summary</div>
    </div>

    <!-- Report Information -->
    <div class="report-info">
        <table>
            <tr>
                <td><strong>Report Type:</strong></td>
                <td>System Activity Report</td>
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
                <td><strong>Period:</strong></td>
                <td>{{ $timePeriodLabel }}</td>
                <td><strong>Date Range:</strong></td>
                <td>{{ $startDate->format('M j, Y') }} - {{ $endDate->format('M j, Y') }}</td>
            </tr>
        </table>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $summary['total_registrations'] }}</div>
            <div class="stat-label">Club Registrations</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $summary['total_renewals'] }}</div>
            <div class="stat-label">Club Renewals</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $summary['total_new_members'] }}</div>
            <div class="stat-label">New Members</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $summary['total_status_changes'] }}</div>
            <div class="stat-label">Status Changes</div>
        </div>
    </div>

    <!-- Club Registration Requests -->
    <div class="activity-section">
        <div class="section-header">
            Club Registration Requests ({{ $registrations->count() }})
        </div>

        @if($registrations->count() > 0)
            <table class="activity-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">Club Name</th>
                        <th style="width: 15%;">Department</th>
                        <th style="width: 15%;">Submitted By</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 15%;">Submitted Date</th>
                        <th style="width: 15%;">Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $index => $registration)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td><strong>{{ $registration->club_name }}</strong></td>
                            <td>{{ $registration->department }}</td>
                            <td>{{ $registration->officer->name ?? 'N/A' }}</td>
                            <td>
                                <span class="status-badge status-{{ $registration->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $registration->status)) }}
                                </span>
                            </td>
                            <td>{{ $registration->submitted_at ? $registration->submitted_at->format('M j, Y') : 'N/A' }}</td>
                            <td>{{ $registration->updated_at->format('M j, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">No club registration requests found for this period.</div>
        @endif
    </div>

    <!-- Club Renewals -->
    <div class="activity-section">
        <div class="section-header">
            Club Renewals ({{ $renewals->count() }})
        </div>

        @if($renewals->count() > 0)
            <table class="activity-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 30%;">Club Name</th>
                        <th style="width: 15%;">Academic Year</th>
                        <th style="width: 12%;">Status</th>
                        <th style="width: 19%;">Submitted Date</th>
                        <th style="width: 19%;">Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($renewals as $index => $renewal)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td><strong>{{ $renewal->club->name ?? 'N/A' }}</strong></td>
                            <td>{{ $renewal->academic_year }}</td>
                            <td>
                                <span class="status-badge status-{{ $renewal->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $renewal->status)) }}
                                </span>
                            </td>
                            <td>{{ $renewal->submitted_at ? $renewal->submitted_at->format('M j, Y') : 'N/A' }}</td>
                            <td>{{ $renewal->updated_at->format('M j, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">No club renewals found for this period.</div>
        @endif
    </div>

    <!-- New Member Registrations -->
    <div class="activity-section">
        <div class="section-header">
            New Member Registrations ({{ $newMembers->count() }})
        </div>

        @if($newMembers->count() > 0)
            <table class="activity-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">Member Name</th>
                        <th style="width: 15%;">Student ID</th>
                        <th style="width: 20%;">Email</th>
                        <th style="width: 15%;">Department</th>
                        <th style="width: 10%;">Role</th>
                        <th style="width: 10%;">Date Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($newMembers->take(100) as $index => $member)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td><strong>{{ $member->name }}</strong></td>
                            <td>
                                @if($member->role === 'adviser')
                                    {{ $member->professor_id ?? 'N/A' }}
                                @else
                                    {{ $member->student_id }}
                                @endif
                            </td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->department }}</td>
                            <td>{{ ucfirst($member->role) }}</td>
                            <td>{{ $member->created_at->format('M j, Y') }}</td>
                        </tr>
                    @endforeach
                    @if($newMembers->count() > 100)
                        <tr>
                            <td colspan="7" style="text-align: center; font-style: italic; color: #666; background-color: #f8f9fa;">
                                ... and {{ $newMembers->count() - 100 }} more new members
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @else
            <div class="no-data">No new member registrations found for this period.</div>
        @endif
    </div>

    <!-- Organization Status Changes -->
    <div class="activity-section">
        <div class="section-header">
            Organization Status Changes ({{ $statusChanges->count() }})
        </div>

        @if($statusChanges->count() > 0)
            <table class="activity-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 30%;">Organization Name</th>
                        <th style="width: 15%;">Department</th>
                        <th style="width: 15%;">Current Status</th>
                        <th style="width: 15%;">Last Updated</th>
                        <th style="width: 10%;">Members</th>
                        <th style="width: 10%;">Officers</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statusChanges as $index => $club)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td><strong>{{ $club->name }}</strong></td>
                            <td>{{ $club->department }}</td>
                            <td>
                                <span class="status-badge status-{{ $club->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $club->status)) }}
                                </span>
                            </td>
                            <td>{{ $club->updated_at->format('M j, Y') }}</td>
                            <td style="text-align: center;">{{ $club->member_count }}</td>
                            <td style="text-align: center;">{{ $club->officers->count() ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">No organization status changes found for this period.</div>
        @endif
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
