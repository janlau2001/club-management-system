<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Club Members Report</title>
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
            text-align: center;
            padding: 12px 6px;
            border: 1px solid #e0e0e0;
            background-color: #fafafa;
        }
        .stat-value {
            font-size: 20pt;
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

        /* ── Club Header ─── */
        .club-header-bar {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .club-header-bar td {
            background-color: #111;
            color: #fff;
            padding: 10px 14px;
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .club-meta-line {
            font-size: 7.5pt;
            color: #888;
            margin-bottom: 14px;
            padding: 0 2px;
        }

        /* ── Sub-section Label ─── */
        .sub-label {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #333;
            padding: 5px 0;
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 6px;
            margin-top: 14px;
        }

        /* ── Data Table ─── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            margin-bottom: 6px;
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

        /* Officers table gets a subtle left accent */
        .officers-table thead th {
            background-color: #1e3a5f;
        }
        .advisers-table thead th {
            background-color: #1a4d3e;
        }

        .text-center { text-align: center; }
        .text-muted { color: #999; }
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

        .page-break {
            page-break-before: always;
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
        <h1>Members Report</h1>
        <p class="subtitle">
            @if($clubs->count() === 1)
                {{ $clubs->first()->name }} &mdash; Membership Roster
            @else
                Complete Membership Directory Across All Organizations
            @endif
        </p>
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

    {{-- ── STATISTICS ── --}}
    <table class="stats-table">
        <tr>
            <td style="width: 20%;">
                <span class="stat-value">{{ $clubs->count() }}</span>
                <span class="stat-label">Organizations</span>
            </td>
            <td style="width: 20%;">
                <span class="stat-value">{{ $totalOfficers }}</span>
                <span class="stat-label">Officers</span>
            </td>
            <td style="width: 20%;">
                <span class="stat-value">{{ $totalAdvisers ?? 0 }}</span>
                <span class="stat-label">Advisers</span>
            </td>
            <td style="width: 20%;">
                <span class="stat-value">{{ $totalMembers }}</span>
                <span class="stat-label">Members</span>
            </td>
            <td style="width: 20%;">
                <span class="stat-value">{{ $totalOfficers + ($totalAdvisers ?? 0) + $totalMembers }}</span>
                <span class="stat-label">Grand Total</span>
            </td>
        </tr>
    </table>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- ── SINGLE CLUB REPORT ────────────────────────────────── --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if($clubs->count() === 1)
        @php $club = $clubs->first(); @endphp

        <table class="club-header-bar">
            <tr><td>{{ $club->name }}</td></tr>
        </table>
        <div class="club-meta-line">
            {{ $club->department }} &nbsp;&bull;&nbsp;
            {{ $club->club_type }} &nbsp;&bull;&nbsp;
            {{ ucfirst(str_replace('_', ' ', $club->status)) }} &nbsp;&bull;&nbsp;
            Adviser: {{ $club->adviser_name ?: 'Not Assigned' }}
        </div>

        {{-- Officers --}}
        @if($club->officers->count() > 0)
            <div class="sub-label">Officers ({{ $club->officers->count() }})</div>
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
        @endif

        {{-- Advisers --}}
        @if($club->advisers->count() > 0)
            <div class="sub-label">Advisers ({{ $club->advisers->count() }})</div>
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
        @endif

        {{-- Members --}}
        <div class="sub-label">Members ({{ $club->members->count() }})</div>
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
            <div class="no-data">No registered members for this organization.</div>
        @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- ── ALL CLUBS REPORT ──────────────────────────────────── --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @else
        @foreach($clubs as $clubIndex => $club)
            @if($clubIndex > 0)
                <div class="page-break"></div>
            @endif

            <table class="club-header-bar">
                <tr><td>{{ $club->name }}</td></tr>
            </table>
            <div class="club-meta-line">
                {{ $club->department }} &nbsp;&bull;&nbsp;
                {{ $club->club_type }} &nbsp;&bull;&nbsp;
                {{ ucfirst(str_replace('_', ' ', $club->status)) }} &nbsp;&bull;&nbsp;
                Adviser: {{ $club->adviser_name ?: 'Not Assigned' }}
            </div>

            {{-- Officers --}}
            @if($club->officers->count() > 0)
                <div class="sub-label">Officers ({{ $club->officers->count() }})</div>
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
                <div class="sub-label">Officers (0)</div>
                <div class="no-data">No officers registered.</div>
            @endif

            {{-- Advisers --}}
            @if($club->advisers->count() > 0)
                <div class="sub-label">Advisers ({{ $club->advisers->count() }})</div>
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
            @endif

            {{-- Members --}}
            <div class="sub-label">Members ({{ $club->members->count() }})</div>
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
                <div class="no-data">No registered members for this organization.</div>
            @endif
        @endforeach
    @endif

    {{-- ── FOOTER ── --}}
    <div class="doc-footer">
        Club Management System &mdash; St. Paul University Philippines &bull; Generated {{ $generatedDate }} at {{ $generatedTime }}<br>
        <span class="conf">Official Document &bull; For Authorized Use Only</span>
    </div>
</body>
</html>
