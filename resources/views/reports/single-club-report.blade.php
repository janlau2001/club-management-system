<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $club->name }} &mdash; Club Report</title>
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
            width: 100px;
        }
        .meta-value {
            color: #111;
            font-weight: 600;
        }

        /* ── Club Overview ─── */
        .overview-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 8pt;
        }
        .overview-table td {
            padding: 8px 10px;
            border: 1px solid #e0e0e0;
            vertical-align: top;
        }
        .overview-label {
            font-size: 6.5pt;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
            display: block;
            margin-bottom: 2px;
        }
        .overview-value {
            font-size: 9pt;
            font-weight: bold;
            color: #111;
        }

        /* ── Status ─── */
        .status {
            font-size: 7pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 3px 8px;
        }
        .status-active { color: #166534; background-color: #dcfce7; }
        .status-suspended { color: #991b1b; background-color: #fee2e2; }
        .status-pending { color: #92400e; background-color: #fef3c7; }

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
            margin-top: 18px;
        }

        /* ── Description Block ─── */
        .desc-block {
            padding: 10px 12px;
            background-color: #fafafa;
            border-left: 3px solid #111;
            font-size: 8.5pt;
            line-height: 1.6;
            color: #333;
            margin-bottom: 16px;
        }

        /* ── Adviser Block ─── */
        .adviser-block {
            padding: 10px 12px;
            background-color: #fafafa;
            border-left: 3px solid #1a4d3e;
            margin-bottom: 16px;
        }
        .adviser-name {
            font-weight: bold;
            font-size: 9pt;
            color: #111;
        }
        .adviser-email {
            font-size: 8pt;
            color: #888;
            margin-top: 2px;
        }

        /* ── Data Table ─── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            margin-bottom: 10px;
        }
        .data-table thead th {
            background-color: #333;
            color: #fff;
            padding: 6px 5px;
            text-align: left;
            font-weight: 600;
            font-size: 6.5pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .data-table tbody td {
            padding: 5px 5px;
            border-bottom: 1px solid #eaeaea;
            vertical-align: top;
        }
        .data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .officers-table thead th { background-color: #1e3a5f; }
        .advisers-table thead th { background-color: #1a4d3e; }

        .text-center { text-align: center; }
        .font-bold { font-weight: 600; }

        .no-data {
            text-align: center;
            color: #999;
            font-style: italic;
            padding: 16px;
            background-color: #fafafa;
            border: 1px dashed #ddd;
            font-size: 8pt;
            margin-bottom: 10px;
        }

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
    {{-- ── HEADER ── --}}
    <div class="doc-header">
        <p class="institution">St. Paul University Philippines</p>
        <p class="office">Office of Student Affairs</p>
        <p class="address">Tuguegarao City, Cagayan 3500</p>
    </div>

    {{-- ── TITLE ── --}}
    <div class="report-title">
        <h1>{{ $club->name }}</h1>
        <p class="subtitle">Individual Organization Report</p>
    </div>

    {{-- ── META ── --}}
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

    {{-- ── CLUB OVERVIEW ── --}}
    <table class="overview-table">
        <tr>
            <td style="width: 33%;">
                <span class="overview-label">Department</span>
                <span class="overview-value">{{ $club->department }}</span>
            </td>
            <td style="width: 33%;">
                <span class="overview-label">Club Type</span>
                <span class="overview-value">{{ $club->club_type }}</span>
            </td>
            <td style="width: 34%;">
                <span class="overview-label">Status</span>
                <span class="status status-{{ $club->status }}">{{ ucfirst(str_replace('_', ' ', $club->status)) }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="overview-label">Date Registered</span>
                <span class="overview-value">{{ $club->date_registered ? $club->date_registered->format('M j, Y') : '—' }}</span>
            </td>
            <td>
                <span class="overview-label">Officers</span>
                <span class="overview-value">{{ $club->officers->count() }}</span>
            </td>
            <td>
                <span class="overview-label">Advisers</span>
                <span class="overview-value">{{ $club->advisers->count() }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="overview-label">Members</span>
                <span class="overview-value">{{ $club->members->count() }}</span>
            </td>
            <td>
                <span class="overview-label">Total Personnel</span>
                <span class="overview-value">{{ $club->officers->count() + $club->advisers->count() + $club->members->count() }}</span>
            </td>
            <td>
                <span class="overview-label">Adviser</span>
                <span class="overview-value">{{ $club->adviser_name ?: '—' }}</span>
            </td>
        </tr>
    </table>

    {{-- ── DESCRIPTION ── --}}
    @if($club->description)
        <div class="section-label">Description</div>
        <div class="desc-block">{{ $club->description }}</div>
    @endif

    {{-- ── ADVISER DETAILS ── --}}
    @if($club->adviser_name)
        <div class="section-label">Club Adviser</div>
        <div class="adviser-block">
            <div class="adviser-name">{{ $club->adviser_name }}</div>
            <div class="adviser-email">{{ $club->adviser_email ?: 'Email not provided' }}</div>
        </div>
    @endif

    {{-- ── OFFICERS ── --}}
    <div class="section-label">Officers ({{ $club->officers->count() }})</div>
    @if($club->officers->count() > 0)
        <table class="data-table officers-table">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">#</th>
                    <th style="width: 35%;">Name</th>
                    <th style="width: 25%;">Position</th>
                    <th style="width: 35%;">Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach($club->officers as $i => $officer)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="font-bold">{{ $officer->name }}</td>
                        <td>{{ $officer->position }}</td>
                        <td>{{ $officer->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">No officers registered.</div>
    @endif

    {{-- ── ADVISERS ── --}}
    <div class="section-label">Advisers ({{ $club->advisers->count() }})</div>
    @if($club->advisers->count() > 0)
        <table class="data-table advisers-table">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">#</th>
                    <th style="width: 25%;">Name</th>
                    <th style="width: 18%;">Position</th>
                    <th style="width: 14%;">Professor ID</th>
                    <th style="width: 16%;">Department</th>
                    <th style="width: 22%;">Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach($club->advisers as $i => $adviser)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="font-bold">{{ $adviser->name }}</td>
                        <td>{{ $adviser->position ?? 'Club Adviser' }}</td>
                        <td>{{ $adviser->professor_id ?? '—' }}</td>
                        <td>{{ $adviser->department_office ?? '—' }}</td>
                        <td>{{ $adviser->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">No advisers registered.</div>
    @endif

    {{-- ── MEMBERS ── --}}
    <div class="section-label">Members ({{ $club->members->count() }})</div>
    @if($club->members->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">#</th>
                    <th style="width: 25%;">Name</th>
                    <th style="width: 14%;">Student ID</th>
                    <th style="width: 26%;">Email</th>
                    <th style="width: 14%;">Year Level</th>
                    <th style="width: 16%;">Date Joined</th>
                </tr>
            </thead>
            <tbody>
                @foreach($club->members as $i => $member)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="font-bold">{{ $member->name }}</td>
                        <td>{{ $member->role === 'adviser' ? ($member->professor_id ?? '—') : $member->student_id }}</td>
                        <td>{{ $member->email }}</td>
                        <td>{{ $member->year_level }}</td>
                        <td>{{ $member->joined_date ? $member->joined_date->format('M j, Y') : '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">No members registered.</div>
    @endif

    {{-- ── FOOTER ── --}}
    <div class="doc-footer">
        Club Management System &mdash; St. Paul University Philippines &bull; Generated {{ $generatedDate }} at {{ $generatedTime }}<br>
        <span class="conf">Official Document &bull; For Authorized Use Only</span>
    </div>
</body>
</html>
