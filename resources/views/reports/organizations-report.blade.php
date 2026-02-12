<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Organizations Report</title>
    <style>
        @page {
            margin: 18mm 14mm 22mm 14mm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 9pt;
            line-height: 1.5;
            color: #1a1a1a;
        }

        /* ── Header ─── */
        .doc-header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2.5px solid #111;
            margin-bottom: 18px;
        }
        .doc-header .institution {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            color: #111;
            margin: 0;
        }
        .doc-header .office {
            font-size: 8.5pt;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #555;
            margin: 3px 0 0 0;
        }
        .doc-header .address {
            font-size: 7.5pt;
            color: #888;
            margin: 3px 0 0 0;
        }

        /* ── Report Title ─── */
        .report-title {
            text-align: center;
            margin-bottom: 16px;
        }
        .report-title h1 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #111;
            margin: 0 0 3px 0;
        }
        .report-title .subtitle {
            font-size: 8pt;
            color: #888;
            margin: 0;
        }

        /* ── Meta ─── */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            font-size: 8pt;
            border: 1px solid #e0e0e0;
        }
        .meta-table td {
            padding: 5px 10px;
            border: 1px solid #e0e0e0;
        }
        .meta-label {
            background-color: #f5f5f5;
            color: #666;
            font-size: 7pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 90px;
        }
        .meta-value {
            color: #111;
            font-weight: 600;
        }

        /* ── Stats ─── */
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }
        .stats-table td {
            width: 25%;
            text-align: center;
            padding: 12px 6px;
            border: 1px solid #e0e0e0;
            background-color: #fafafa;
        }
        .stat-value {
            font-size: 22pt;
            font-weight: bold;
            color: #111;
            display: block;
            line-height: 1.1;
        }
        .stat-label {
            font-size: 6.5pt;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 3px;
            display: block;
        }

        /* ── Section Label ─── */
        .section-label {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #111;
            padding-bottom: 5px;
            border-bottom: 1px solid #ccc;
            margin-bottom: 8px;
        }

        /* ── Data Table ─── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }
        .data-table thead th {
            background-color: #111;
            color: #fff;
            padding: 7px 5px;
            text-align: left;
            font-weight: 600;
            font-size: 6.5pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .data-table tbody td {
            padding: 6px 5px;
            border-bottom: 1px solid #eaeaea;
            vertical-align: top;
        }
        .data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .org-name {
            font-weight: 600;
            color: #111;
        }
        .org-dept {
            font-size: 7pt;
            color: #999;
            margin-top: 1px;
        }
        .text-center { text-align: center; }

        /* ── Status ─── */
        .status {
            font-size: 6.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 2px 6px;
        }
        .status-active { color: #166534; background-color: #dcfce7; }
        .status-suspended { color: #991b1b; background-color: #fee2e2; }
        .status-pending { color: #92400e; background-color: #fef3c7; }

        /* ── Footer ─── */
        .doc-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7pt;
            color: #aaa;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
        .doc-footer .conf {
            font-size: 6pt;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #ccc;
            margin-top: 2px;
        }
    </style>
</head>
<body>
    <div class="doc-header">
        <p class="institution">St. Paul University Philippines</p>
        <p class="office">Office of Student Affairs</p>
        <p class="address">Tuguegarao City, Cagayan 3500</p>
    </div>

    <div class="report-title">
        <h1>Organizations Report</h1>
        <p class="subtitle">Complete Overview of All Registered Organizations</p>
    </div>

    <table class="meta-table">
        <tr>
            <td class="meta-label">Prepared by</td>
            <td class="meta-value">{{ $adminName }}</td>
            <td class="meta-label">Date</td>
            <td class="meta-value">{{ $generatedDate }}</td>
        </tr>
        <tr>
            <td class="meta-label">Designation</td>
            <td class="meta-value">{{ $adminRole }}</td>
            <td class="meta-label">Time</td>
            <td class="meta-value">{{ $generatedTime }}</td>
        </tr>
    </table>

    <table class="stats-table">
        <tr>
            <td>
                <span class="stat-value">{{ $totalClubs }}</span>
                <span class="stat-label">Total Organizations</span>
            </td>
            <td>
                <span class="stat-value">{{ $activeClubs }}</span>
                <span class="stat-label">Active</span>
            </td>
            <td>
                <span class="stat-value">{{ $suspendedClubs }}</span>
                <span class="stat-label">Suspended</span>
            </td>
            <td>
                <span class="stat-value">{{ $clubs->sum('member_count') }}</span>
                <span class="stat-label">Total Members</span>
            </td>
        </tr>
    </table>

    <div class="section-label">Registered Organizations</div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">#</th>
                <th style="width: 24%;">Organization</th>
                <th style="width: 10%;">Type</th>
                <th style="width: 9%;">Status</th>
                <th style="width: 12%;">Registered</th>
                <th style="width: 8%;" class="text-center">Members</th>
                <th style="width: 8%;" class="text-center">Officers</th>
                <th style="width: 8%;" class="text-center">Advisers</th>
                <th style="width: 17%;">Adviser</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clubs as $index => $club)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <span class="org-name">{{ $club->name }}</span><br>
                        <span class="org-dept">{{ $club->department }}</span>
                    </td>
                    <td>{{ $club->club_type }}</td>
                    <td>
                        <span class="status status-{{ $club->status }}">
                            {{ ucfirst(str_replace('_', ' ', $club->status)) }}
                        </span>
                    </td>
                    <td>{{ $club->date_registered ? $club->date_registered->format('M j, Y') : '—' }}</td>
                    <td class="text-center">{{ $club->members->count() }}</td>
                    <td class="text-center">{{ $club->officers->count() }}</td>
                    <td class="text-center">{{ $club->advisers->count() }}</td>
                    <td>{{ $club->adviser_name ?: '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="doc-footer">
        Club Management System &mdash; St. Paul University Philippines &bull; Generated {{ $generatedDate }} at {{ $generatedTime }}<br>
        <span class="conf">Official Document &bull; For Authorized Use Only</span>
    </div>
</body>
</html>
