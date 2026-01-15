<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $club->name }} - Club Report</title>
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
        
        .club-title-section {
            text-align: center;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 10px;
            border-radius: 5px;
        }

        .club-main-title {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }

        .club-subtitle {
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
        
        .club-overview {
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 12px;
        }

        .overview-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .overview-item {
            background-color: white;
            padding: 6px;
            border-radius: 3px;
            border-left: 2px solid #007bff;
        }

        .overview-label {
            font-size: 7px;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .overview-value {
            font-size: 10px;
            font-weight: bold;
            color: #333;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-active { background-color: #d4edda; color: #155724; }
        .status-suspended { background-color: #f8d7da; color: #721c24; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        
        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            border-bottom: 1px solid #007bff;
            padding-bottom: 3px;
        }

        .officers-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 10px;
        }

        .officer-card {
            background-color: #f8f9fa;
            padding: 6px;
            border-radius: 3px;
            border-left: 2px solid #6f42c1;
        }

        .officer-name {
            font-weight: bold;
            font-size: 8px;
            color: #333;
            margin-bottom: 2px;
        }

        .officer-position {
            color: #6f42c1;
            font-size: 7px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .officer-email {
            color: #666;
            font-size: 7px;
        }
        
        .members-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .members-table th,
        .members-table td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
            font-size: 7px;
        }

        .members-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .members-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px dashed #ddd;
            font-size: 8px;
        }

        .description-section {
            background-color: #f8f9fa;
            padding: 8px;
            border-radius: 5px;
            margin-bottom: 10px;
            border-left: 3px solid #28a745;
        }

        .description-text {
            font-size: 8px;
            line-height: 1.4;
            color: #333;
        }

        .adviser-info {
            background-color: #fff3cd;
            padding: 8px;
            border-radius: 5px;
            margin-bottom: 10px;
            border-left: 3px solid #ffc107;
        }

        .adviser-title {
            font-weight: bold;
            color: #856404;
            margin-bottom: 3px;
            font-size: 9px;
        }

        .adviser-details {
            font-size: 8px;
            color: #856404;
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

        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
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

    <!-- Club Title Section -->
    <div class="club-title-section">
        <div class="club-main-title">{{ $club->name }}</div>
        <div class="club-subtitle">Official Club Report</div>
    </div>

    <!-- Report Information -->
    <div class="report-info">
        <table>
            <tr>
                <td><strong>Report Type:</strong></td>
                <td>Individual Club Report</td>
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
                <td><strong>Club Name:</strong></td>
                <td>{{ $club->name }}</td>
                <td><strong>Report Status:</strong></td>
                <td>Official</td>
            </tr>
        </table>
    </div>

    <!-- Club Details Section -->
    <div class="club-overview">
        <div class="overview-grid">
            <div class="overview-item">
                <div class="overview-label">Department</div>
                <div class="overview-value">{{ $club->department }}</div>
            </div>
            <div class="overview-item">
                <div class="overview-label">Club Type</div>
                <div class="overview-value">{{ $club->club_type }}</div>
            </div>
            <div class="overview-item">
                <div class="overview-label">Status</div>
                <div class="overview-value">
                    <span class="status-badge status-{{ $club->status }}">
                        {{ ucfirst(str_replace('_', ' ', $club->status)) }}
                    </span>
                </div>
            </div>
            <div class="overview-item">
                <div class="overview-label">Date Registered</div>
                <div class="overview-value">{{ $club->date_registered ? $club->date_registered->format('M j, Y') : 'N/A' }}</div>
            </div>
            <div class="overview-item">
                <div class="overview-label">Total Members</div>
                <div class="overview-value">{{ $club->member_count }}</div>
            </div>
            <div class="overview-item">
                <div class="overview-label">Total Officers</div>
                <div class="overview-value">{{ $club->officers->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Adviser Information -->
    @if($club->adviser_name)
        <div class="section">
            <div class="section-title">Club Adviser</div>
            <div class="adviser-info">
                <div class="adviser-title">{{ $club->adviser_name }}</div>
                <div class="adviser-details">{{ $club->adviser_email ?: 'Email not provided' }}</div>
            </div>
        </div>
    @endif

    <!-- Club Description -->
    @if($club->description)
        <div class="section">
            <div class="section-title">Club Description</div>
            <div class="description-section">
                <div class="description-text">{{ $club->description }}</div>
            </div>
        </div>
    @endif

    <!-- Officers Section -->
    <div class="section">
        <div class="section-title">Club Officers ({{ $club->officers->count() }})</div>
        @if($club->officers->count() > 0)
            <table class="members-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 35%;">Name</th>
                        <th style="width: 25%;">Position</th>
                        <th style="width: 35%;">Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($club->officers as $index => $officer)
                        <tr style="background-color: #f0f8ff;">
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td><strong>{{ $officer->name }}</strong></td>
                            <td><span style="color: #6f42c1; font-weight: bold;">{{ $officer->position }}</span></td>
                            <td>{{ $officer->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">No officers registered</div>
        @endif
    </div>

    <!-- Members Section -->
    <div class="section">
        <div class="section-title">Club Members ({{ $club->members->count() }})</div>
        @if($club->members->count() > 0)
            <table class="members-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">Name</th>
                        <th style="width: 15%;">Student ID</th>
                        <th style="width: 25%;">Email</th>
                        <th style="width: 15%;">Year Level</th>
                        <th style="width: 15%;">Date Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($club->members as $index => $member)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td><strong>{{ $member->name }}</strong></td>
                            <td>{{ $member->student_id }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->year_level }}</td>
                            <td>{{ $member->joined_date ? $member->joined_date->format('M j, Y') : 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">No members registered</div>
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
