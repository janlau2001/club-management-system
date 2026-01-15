<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Members Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 11px;
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
            gap: 15px;
            margin-bottom: 25px;
            padding: 0;
        }

        .stat-item {
            text-align: center;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 20px 15px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.1);
        }

        .stat-number {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .stat-label {
            font-size: 11px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .club-section {
            margin-bottom: 25px;
        }

        .club-header {
            text-align: center;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .club-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            padding: 12px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .club-details {
            font-size: 10px;
            color: #666;
            line-height: 1.4;
            margin-top: 5px;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #333;
            margin: 15px 0 8px 0;
            padding: 6px 12px;
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            border-radius: 3px;
        }

        .officers-section {
            margin-bottom: 20px;
        }

        .members-section {
            margin-bottom: 20px;
        }        .officers-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .officer-card {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #6f42c1;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .officer-name {
            font-weight: bold;
            font-size: 12px;
            color: #333;
            margin-bottom: 5px;
        }

        .officer-position {
            color: #6f42c1;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .officer-email {
            color: #666;
            font-size: 10px;
        }
        
        .members-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .members-table th,
        .members-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 10px;
        }

        .members-table th {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            font-weight: bold;
        }

        .members-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .members-table tr:hover {
            background-color: #e3f2fd;
        }

        .no-members {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 8px;
            font-size: 12px;
            border: 2px dashed #ddd;
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

        .page-break {
            page-break-before: always;
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

    <!-- Report Information -->
    <div class="report-info">
        <table>
            <tr>
                <td><strong>Report Type:</strong></td>
                <td>Club Members Report</td>
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
                <td><strong>Total Clubs:</strong></td>
                <td>{{ $clubs->count() }}</td>
                <td><strong>Report Status:</strong></td>
                <td>Official</td>
            </tr>
        </table>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $clubs->count() }}</div>
            <div class="stat-label">Total Clubs</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $totalMembers }}</div>
            <div class="stat-label">Total Members</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $totalOfficers }}</div>
            <div class="stat-label">Total Officers</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $clubs->where('status', 'active')->count() }}</div>
            <div class="stat-label">Active Clubs</div>
        </div>
    </div>

    <!-- Check if it's a specific club report or all clubs -->
    @if($clubs->count() === 1)
        <!-- Single Club Members Report -->
        @php $singleClub = $clubs->first(); @endphp
        
        <!-- Club Name Header -->
        <div style="text-align: center; margin-bottom: 15px;">
            <h2 style="font-size: 16px; font-weight: bold; color: #333; margin: 0; padding: 10px; background: linear-gradient(135deg, #007bff, #0056b3); color: white; border-radius: 5px;">
                {{ $singleClub->name }}
            </h2>
            <div style="font-size: 10px; color: #666; margin-top: 5px;">
                {{ $singleClub->department }} • {{ $singleClub->club_type }} • 
                {{ ucfirst(str_replace('_', ' ', $singleClub->status)) }} • 
                Adviser: {{ $singleClub->adviser_name ?: 'Not Assigned' }}
            </div>
        </div>

        <!-- Officers Table -->
        @if($singleClub->officers->count() > 0)
            <div class="officers-section">
                <div class="section-title">Club Officers ({{ $singleClub->officers->count() }})</div>
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
                        @foreach($singleClub->officers as $index => $officer)
                            <tr style="background-color: #e3f2fd;">
                                <td style="text-align: center;">{{ $index + 1 }}</td>
                                <td><strong>{{ $officer->name }}</strong></td>
                                <td><span style="color: #1976d2; font-weight: bold;">{{ $officer->position }}</span></td>
                                <td>{{ $officer->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Members Table -->
        <div class="members-section">
            <div class="section-title">Club Members ({{ $singleClub->members->count() }})</div>
            @if($singleClub->members->count() > 0)
                <table class="members-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 25%;">Name</th>
                            <th style="width: 15%;">Student ID</th>
                            <th style="width: 30%;">Email</th>
                            <th style="width: 15%;">Year Level</th>
                            <th style="width: 10%;">Date Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($singleClub->members as $index => $member)
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
                <div class="no-members">
                    No registered members for this club.
                </div>
            @endif
        </div>
    @else
        <!-- All Clubs Members Report -->
        @foreach($clubs as $clubIndex => $club)
            <div class="club-section" @if($clubIndex > 0) style="page-break-before: always;" @endif>
                
                <!-- Club Name Header -->
                <div style="text-align: center; margin-bottom: 15px;">
                    <h2 style="font-size: 16px; font-weight: bold; color: #333; margin: 0; padding: 10px; background: linear-gradient(135deg, #007bff, #0056b3); color: white; border-radius: 5px;">
                        {{ $club->name }}
                    </h2>
                    <div style="font-size: 10px; color: #666; margin-top: 5px;">
                        {{ $club->department }} • {{ $club->club_type }} • 
                        {{ ucfirst(str_replace('_', ' ', $club->status)) }} • 
                        Adviser: {{ $club->adviser_name ?: 'Not Assigned' }}
                    </div>
                </div>

                <!-- Officers Table -->
                @if($club->officers->count() > 0)
                    <div class="officers-section">
                        <div class="section-title">Club Officers ({{ $club->officers->count() }})</div>
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
                                    <tr style="background-color: #e3f2fd;">
                                        <td style="text-align: center;">{{ $index + 1 }}</td>
                                        <td><strong>{{ $officer->name }}</strong></td>
                                        <td><span style="color: #1976d2; font-weight: bold;">{{ $officer->position }}</span></td>
                                        <td>{{ $officer->email }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="officers-section">
                        <div class="section-title">Club Officers (0)</div>
                        <div class="no-members">No officers registered for this club.</div>
                    </div>
                @endif

                <!-- Members Table -->
                <div class="members-section">
                    <div class="section-title">Club Members ({{ $club->members->count() }})</div>
                    @if($club->members->count() > 0)
                        <table class="members-table">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 25%;">Name</th>
                                    <th style="width: 15%;">Student ID</th>
                                    <th style="width: 30%;">Email</th>
                                    <th style="width: 15%;">Year Level</th>
                                    <th style="width: 10%;">Date Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($club->members as $index => $member)
                                    <tr>
                                        <td style="text-align: center;">{{ $index + 1 }}</td>
                                        <td>{{ $member->name }}</td>
                                        <td>{{ $member->student_id }}</td>
                                        <td>{{ $member->email }}</td>
                                        <td>{{ $member->year_level }}</td>
                                        <td>{{ $member->joined_date ? $member->joined_date->format('M j, Y') : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="no-members">
                            No registered members for this club.
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

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
