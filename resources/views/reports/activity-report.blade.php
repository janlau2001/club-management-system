<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activity Report &mdash; {{ $timePeriodLabel }}</title>
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
            padding: 7px 10px;
            background-color: #f0f0f0;
            border-left: 4px solid #111;
            margin-bottom: 8px;
            margin-top: 20px;
        }
        .section-count {
            font-weight: normal;
            color: #888;
            font-size: 8pt;
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

        .text-center { text-align: center; }
        .font-bold { font-weight: 600; }

        /* ── Status ─── */
        .status {
            font-size: 6.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 2px 6px;
        }
        .status-pending { color: #92400e; background-color: #fef3c7; }
        .status-approved { color: #166534; background-color: #dcfce7; }
        .status-rejected { color: #991b1b; background-color: #fee2e2; }
        .status-active { color: #166534; background-color: #dcfce7; }
        .status-suspended { color: #991b1b; background-color: #fee2e2; }
        .status-draft { color: #4b5563; background-color: #f3f4f6; }

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
        <h1>Activity Report</h1>
        <p class="subtitle">{{ $timePeriodLabel }} &mdash; {{ $startDate->format('M j, Y') }} to {{ $endDate->format('M j, Y') }}</p>
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
        <tr>
            <td class="meta-label">Period</td>
            <td class="meta-value">{{ $timePeriodLabel }}</td>
            <td class="meta-label">Date Range</td>
            <td class="meta-value">{{ $startDate->format('M j, Y') }} &ndash; {{ $endDate->format('M j, Y') }}</td>
        </tr>
    </table>

    {{-- ── STATISTICS ── --}}
    <table class="stats-table">
        <tr>
            <td>
                <span class="stat-value">{{ $summary['total_registrations'] }}</span>
                <span class="stat-label">Registrations</span>
            </td>
            <td>
                <span class="stat-value">{{ $summary['total_renewals'] }}</span>
                <span class="stat-label">Renewals</span>
            </td>
            <td>
                <span class="stat-value">{{ $summary['total_new_members'] }}</span>
                <span class="stat-label">New Members</span>
            </td>
            <td>
                <span class="stat-value">{{ $summary['total_status_changes'] }}</span>
                <span class="stat-label">Status Changes</span>
            </td>
        </tr>
    </table>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- CLUB REGISTRATION REQUESTS                         --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <div class="section-label">
        Club Registration Requests <span class="section-count">({{ $registrations->count() }})</span>
    </div>

    @if($registrations->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 4%;" class="text-center">#</th>
                    <th style="width: 24%;">Club Name</th>
                    <th style="width: 14%;">Department</th>
                    <th style="width: 16%;">Submitted By</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 16%;">Submitted</th>
                    <th style="width: 16%;">Updated</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $i => $reg)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="font-bold">{{ $reg->club_name }}</td>
                        <td>{{ $reg->department }}</td>
                        <td>{{ $reg->officer->name ?? '—' }}</td>
                        <td>
                            <span class="status status-{{ $reg->status }}">
                                {{ ucfirst(str_replace('_', ' ', $reg->status)) }}
                            </span>
                        </td>
                        <td>{{ $reg->submitted_at ? $reg->submitted_at->format('M j, Y') : '—' }}</td>
                        <td>{{ $reg->updated_at->format('M j, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">No club registration requests found for this period.</div>
    @endif

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- CLUB RENEWALS                                      --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <div class="section-label">
        Club Renewals <span class="section-count">({{ $renewals->count() }})</span>
    </div>

    @if($renewals->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 4%;" class="text-center">#</th>
                    <th style="width: 28%;">Club Name</th>
                    <th style="width: 18%;">Academic Year</th>
                    <th style="width: 12%;">Status</th>
                    <th style="width: 19%;">Submitted</th>
                    <th style="width: 19%;">Updated</th>
                </tr>
            </thead>
            <tbody>
                @foreach($renewals as $i => $renewal)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="font-bold">{{ $renewal->club->name ?? '—' }}</td>
                        <td>{{ $renewal->academic_year }}</td>
                        <td>
                            <span class="status status-{{ $renewal->status }}">
                                {{ ucfirst(str_replace('_', ' ', $renewal->status)) }}
                            </span>
                        </td>
                        <td>{{ $renewal->submitted_at ? $renewal->submitted_at->format('M j, Y') : '—' }}</td>
                        <td>{{ $renewal->updated_at->format('M j, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">No club renewals found for this period.</div>
    @endif

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- NEW MEMBER REGISTRATIONS                           --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <div class="section-label">
        New Member Registrations <span class="section-count">({{ $newMembers->count() }})</span>
    </div>

    @if($newMembers->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 4%;" class="text-center">#</th>
                    <th style="width: 22%;">Name</th>
                    <th style="width: 12%;">Student ID</th>
                    <th style="width: 22%;">Email</th>
                    <th style="width: 14%;">Department</th>
                    <th style="width: 10%;">Role</th>
                    <th style="width: 16%;">Joined</th>
                </tr>
            </thead>
            <tbody>
                @foreach($newMembers->take(100) as $i => $member)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="font-bold">{{ $member->name }}</td>
                        <td>{{ $member->role === 'adviser' ? ($member->professor_id ?? '—') : $member->student_id }}</td>
                        <td>{{ $member->email }}</td>
                        <td>{{ $member->department }}</td>
                        <td>{{ ucfirst($member->role) }}</td>
                        <td>{{ $member->created_at->format('M j, Y') }}</td>
                    </tr>
                @endforeach
                @if($newMembers->count() > 100)
                    <tr>
                        <td colspan="7" style="text-align: center; color: #999; font-style: italic; padding: 10px; background-color: #fafafa;">
                            &hellip; and {{ $newMembers->count() - 100 }} additional members
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    @else
        <div class="no-data">No new member registrations found for this period.</div>
    @endif

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- ORGANIZATION STATUS CHANGES                        --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <div class="section-label">
        Status Changes <span class="section-count">({{ $statusChanges->count() }})</span>
    </div>

    @if($statusChanges->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 4%;" class="text-center">#</th>
                    <th style="width: 28%;">Organization</th>
                    <th style="width: 16%;">Department</th>
                    <th style="width: 12%;">Status</th>
                    <th style="width: 16%;">Updated</th>
                    <th style="width: 12%;" class="text-center">Members</th>
                    <th style="width: 12%;" class="text-center">Officers</th>
                </tr>
            </thead>
            <tbody>
                @foreach($statusChanges as $i => $club)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="font-bold">{{ $club->name }}</td>
                        <td>{{ $club->department }}</td>
                        <td>
                            <span class="status status-{{ $club->status }}">
                                {{ ucfirst(str_replace('_', ' ', $club->status)) }}
                            </span>
                        </td>
                        <td>{{ $club->updated_at->format('M j, Y') }}</td>
                        <td class="text-center">{{ $club->member_count }}</td>
                        <td class="text-center">{{ $club->officers->count() ?? 0 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">No organization status changes found for this period.</div>
    @endif

    {{-- ── FOOTER ── --}}
    <div class="doc-footer">
        Club Management System &mdash; St. Paul University Philippines &bull; Generated {{ $generatedDate }} at {{ $generatedTime }}<br>
        <span class="conf">Official Document &bull; For Authorized Use Only</span>
    </div>
</body>
</html>
