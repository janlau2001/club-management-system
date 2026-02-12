<x-dashboard-layout>
    <x-slot name="title">Reports</x-slot>

    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-900">Reports</h1>
        <p class="text-sm text-gray-500 mt-1">Generate and download system reports</p>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Organizations</p>
            <p class="text-3xl font-semibold text-gray-900">{{ \App\Models\Club::count() }}</p>
        </div>
        <div class="bg-white border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Total Members</p>
            <p class="text-3xl font-semibold text-gray-900">{{ \App\Models\ClubUser::count() }}</p>
        </div>
        <div class="bg-white border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Active Clubs</p>
            <p class="text-3xl font-semibold text-gray-900">{{ \App\Models\Club::where('status', 'active')->count() }}</p>
        </div>
        <div class="bg-white border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Recent Activities</p>
            <p class="text-3xl font-semibold text-gray-900">{{ \App\Models\ClubRegistrationRequest::where('created_at', '>=', now()->subDays(30))->count() + \App\Models\ClubRenewal::where('created_at', '>=', now()->subDays(30))->count() }}</p>
        </div>
    </div>

    {{-- Report Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-data="reportManager()">

        {{-- Organization Report Card --}}
        <div class="bg-white border border-gray-200 p-6">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Report</p>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Organizations</h3>
            <p class="text-sm text-gray-500 mb-5">Overview of all registered organizations, status, and membership counts.</p>
            <button @click="openOrgModal()" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium hover:bg-gray-800 transition-colors">
                Generate Report
            </button>
        </div>

        {{-- Members Report Card --}}
        <div class="bg-white border border-gray-200 p-6">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Report</p>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Membership</h3>
            <p class="text-sm text-gray-500 mb-5">Complete member rosters, officers, and advisers per organization.</p>
            <button @click="openMemberModal()" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium hover:bg-gray-800 transition-colors">
                Generate Report
            </button>
        </div>

        {{-- Activity Report Card --}}
        <div class="bg-white border border-gray-200 p-6">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Report</p>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Activity</h3>
            <p class="text-sm text-gray-500 mb-5">Registrations, renewals, and system activity over a chosen period.</p>
            <button @click="openActivityModal()" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium hover:bg-gray-800 transition-colors">
                Generate Report
            </button>
        </div>

        {{-- ══════════════════════════════════════════════════════ --}}
        {{-- ORGANIZATION REPORT MODAL                             --}}
        {{-- ══════════════════════════════════════════════════════ --}}
        <div x-show="showOrgModal" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" @click="closeOrgModal()"></div>

            {{-- Modal --}}
            <div class="relative bg-white border border-gray-200 w-full max-w-md z-10"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Organization Report</h3>
                    <button @click="closeOrgModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('head-office.reports.organizations') }}" @submit="handleFormSubmit($event)">
                    @csrf
                    <div class="px-6 py-5 space-y-5">
                        {{-- Report Scope --}}
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-3">Scope</p>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="report_type" value="all" x-model="orgReportType" class="sr-only">
                                    <div class="border p-3 text-center transition-all"
                                         :class="orgReportType === 'all' ? 'border-gray-900 bg-gray-50' : 'border-gray-200 hover:border-gray-300'">
                                        <p class="text-sm font-medium text-gray-900">All Organizations</p>
                                        <p class="text-xs text-gray-500 mt-0.5">Complete system report</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="report_type" value="specific" x-model="orgReportType" class="sr-only">
                                    <div class="border p-3 text-center transition-all"
                                         :class="orgReportType === 'specific' ? 'border-gray-900 bg-gray-50' : 'border-gray-200 hover:border-gray-300'">
                                        <p class="text-sm font-medium text-gray-900">Specific</p>
                                        <p class="text-xs text-gray-500 mt-0.5">Single organization</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Preview (All) --}}
                        <div x-show="orgReportType === 'all'" x-cloak>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Preview</p>
                            <div class="border border-gray-200 max-h-32 overflow-y-auto">
                                @foreach(\App\Models\Club::orderBy('name')->take(5)->get() as $club)
                                    <div class="flex items-center justify-between px-3 py-2 border-b border-gray-100 last:border-b-0">
                                        <span class="text-sm text-gray-900">{{ $club->name }}</span>
                                        <span class="text-xs text-gray-500">{{ $club->department }}</span>
                                    </div>
                                @endforeach
                                @if(\App\Models\Club::count() > 5)
                                    <div class="px-3 py-2 text-xs text-gray-400 text-center bg-gray-50">
                                        + {{ \App\Models\Club::count() - 5 }} more
                                    </div>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mt-2 text-center">{{ \App\Models\Club::count() }} organizations will be included</p>
                        </div>

                        {{-- Specific Selection --}}
                        <div x-show="orgReportType === 'specific'" x-cloak>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Select Organization</p>
                            <select name="club_id" class="w-full px-3 py-2.5 border border-gray-200 bg-white text-sm text-gray-900 focus:outline-none focus:border-gray-900 transition-colors">
                                <option value="">Choose an organization...</option>
                                @foreach(\App\Models\Club::orderBy('name')->get() as $club)
                                    <option value="{{ $club->id }}">{{ $club->name }} ({{ $club->department }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Password --}}
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Admin Password</p>
                            <input type="password" name="admin_password" required
                                   class="w-full px-3 py-2.5 border border-gray-200 text-sm text-gray-900 focus:outline-none focus:border-gray-900 transition-colors"
                                   placeholder="Enter password to confirm">
                            <p class="text-xs text-gray-400 mt-1.5">Required for report generation</p>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200">
                        <button type="button" @click="closeOrgModal()" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-gray-900 text-white text-sm font-medium hover:bg-gray-800 transition-colors">
                            Generate PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════ --}}
        {{-- MEMBERSHIP REPORT MODAL                               --}}
        {{-- ══════════════════════════════════════════════════════ --}}
        <div x-show="showMemberModal" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" @click="closeMemberModal()"></div>

            <div class="relative bg-white border border-gray-200 w-full max-w-md z-10"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Membership Report</h3>
                    <button @click="closeMemberModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('head-office.reports.members') }}" @submit="handleFormSubmit($event)">
                    @csrf
                    <div class="px-6 py-5 space-y-5">
                        {{-- Report Scope --}}
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-3">Scope</p>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="report_type" value="all" x-model="memberReportType" class="sr-only">
                                    <div class="border p-3 text-center transition-all"
                                         :class="memberReportType === 'all' ? 'border-gray-900 bg-gray-50' : 'border-gray-200 hover:border-gray-300'">
                                        <p class="text-sm font-medium text-gray-900">All Organizations</p>
                                        <p class="text-xs text-gray-500 mt-0.5">Complete membership data</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="report_type" value="specific" x-model="memberReportType" class="sr-only">
                                    <div class="border p-3 text-center transition-all"
                                         :class="memberReportType === 'specific' ? 'border-gray-900 bg-gray-50' : 'border-gray-200 hover:border-gray-300'">
                                        <p class="text-sm font-medium text-gray-900">Specific</p>
                                        <p class="text-xs text-gray-500 mt-0.5">Single organization</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Preview (All) --}}
                        <div x-show="memberReportType === 'all'" x-cloak>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Preview</p>
                            <div class="border border-gray-200 max-h-32 overflow-y-auto">
                                @foreach(\App\Models\Club::orderBy('name')->take(5)->get() as $club)
                                    <div class="flex items-center justify-between px-3 py-2 border-b border-gray-100 last:border-b-0">
                                        <span class="text-sm text-gray-900">{{ $club->name }}</span>
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs text-gray-900 font-medium">{{ $club->member_count }} members</span>
                                            <span class="text-xs text-gray-500">{{ $club->department }}</span>
                                        </div>
                                    </div>
                                @endforeach
                                @if(\App\Models\Club::count() > 5)
                                    <div class="px-3 py-2 text-xs text-gray-400 text-center bg-gray-50">
                                        + {{ \App\Models\Club::count() - 5 }} more
                                    </div>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mt-2 text-center">{{ \App\Models\Club::count() }} organizations &middot; {{ \App\Models\ClubUser::count() }} total members</p>
                        </div>

                        {{-- Specific Selection --}}
                        <div x-show="memberReportType === 'specific'" x-cloak>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Select Organization</p>
                            <select name="club_id" class="w-full px-3 py-2.5 border border-gray-200 bg-white text-sm text-gray-900 focus:outline-none focus:border-gray-900 transition-colors">
                                <option value="">Choose an organization...</option>
                                @foreach(\App\Models\Club::orderBy('name')->get() as $club)
                                    <option value="{{ $club->id }}">{{ $club->name }} ({{ $club->member_count }} members)</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Password --}}
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Admin Password</p>
                            <input type="password" name="admin_password" required
                                   class="w-full px-3 py-2.5 border border-gray-200 text-sm text-gray-900 focus:outline-none focus:border-gray-900 transition-colors"
                                   placeholder="Enter password to confirm">
                            <p class="text-xs text-gray-400 mt-1.5">Required for report generation</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200">
                        <button type="button" @click="closeMemberModal()" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-gray-900 text-white text-sm font-medium hover:bg-gray-800 transition-colors">
                            Generate PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════ --}}
        {{-- ACTIVITY REPORT MODAL                                 --}}
        {{-- ══════════════════════════════════════════════════════ --}}
        <div x-show="showActivityModal" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" @click="closeActivityModal()"></div>

            <div class="relative bg-white border border-gray-200 w-full max-w-md z-10"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Activity Report</h3>
                    <button @click="closeActivityModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('head-office.reports.activities') }}" @submit="handleFormSubmit($event)">
                    @csrf
                    <div class="px-6 py-5 space-y-5">
                        {{-- Time Period --}}
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-3">Time Period</p>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="time_period" value="last_day" x-model="activityPeriod" class="sr-only">
                                    <div class="border p-3 text-center transition-all"
                                         :class="activityPeriod === 'last_day' ? 'border-gray-900 bg-gray-50' : 'border-gray-200 hover:border-gray-300'">
                                        <p class="text-sm font-medium text-gray-900">Last Day</p>
                                        <p class="text-xs text-gray-500 mt-0.5">24 hours</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="time_period" value="last_week" x-model="activityPeriod" class="sr-only">
                                    <div class="border p-3 text-center transition-all"
                                         :class="activityPeriod === 'last_week' ? 'border-gray-900 bg-gray-50' : 'border-gray-200 hover:border-gray-300'">
                                        <p class="text-sm font-medium text-gray-900">Last Week</p>
                                        <p class="text-xs text-gray-500 mt-0.5">7 days</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="time_period" value="last_month" x-model="activityPeriod" class="sr-only">
                                    <div class="border p-3 text-center transition-all"
                                         :class="activityPeriod === 'last_month' ? 'border-gray-900 bg-gray-50' : 'border-gray-200 hover:border-gray-300'">
                                        <p class="text-sm font-medium text-gray-900">Last Month</p>
                                        <p class="text-xs text-gray-500 mt-0.5">30 days</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Activity Summary --}}
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Summary (Last 30 days)</p>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="border border-gray-200 p-3 text-center">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Activities</p>
                                    <p class="text-xl font-semibold text-gray-900 mt-1">{{ \App\Models\ClubRegistrationRequest::where('created_at', '>=', now()->subDays(30))->count() + \App\Models\ClubRenewal::where('created_at', '>=', now()->subDays(30))->count() }}</p>
                                </div>
                                <div class="border border-gray-200 p-3 text-center">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">New Members</p>
                                    <p class="text-xl font-semibold text-gray-900 mt-1">{{ \App\Models\ClubUser::where('created_at', '>=', now()->subDays(30))->count() }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Password --}}
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Admin Password</p>
                            <input type="password" name="admin_password" required
                                   class="w-full px-3 py-2.5 border border-gray-200 text-sm text-gray-900 focus:outline-none focus:border-gray-900 transition-colors"
                                   placeholder="Enter password to confirm">
                            <p class="text-xs text-gray-400 mt-1.5">Required for report generation</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200">
                        <button type="button" @click="closeActivityModal()" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-gray-900 text-white text-sm font-medium hover:bg-gray-800 transition-colors">
                            Generate PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function reportManager() {
            return {
                showOrgModal: false,
                showMemberModal: false,
                showActivityModal: false,
                orgReportType: 'all',
                memberReportType: 'all',
                activityPeriod: 'last_month',
                isSubmitting: false,

                openOrgModal() {
                    this.showOrgModal = true;
                    this.orgReportType = 'all';
                    setTimeout(() => {
                        const f = document.querySelector('form[action*="organizations"] input[name="admin_password"]');
                        if (f) f.value = '';
                    }, 100);
                },
                closeOrgModal() { this.showOrgModal = false; this.isSubmitting = false; },

                openMemberModal() {
                    this.showMemberModal = true;
                    this.memberReportType = 'all';
                    setTimeout(() => {
                        const f = document.querySelector('form[action*="members"] input[name="admin_password"]');
                        if (f) f.value = '';
                    }, 100);
                },
                closeMemberModal() { this.showMemberModal = false; this.isSubmitting = false; },

                openActivityModal() {
                    this.showActivityModal = true;
                    this.activityPeriod = 'last_month';
                    setTimeout(() => {
                        const f = document.querySelector('form[action*="activities"] input[name="admin_password"]');
                        if (f) f.value = '';
                    }, 100);
                },
                closeActivityModal() { this.showActivityModal = false; this.isSubmitting = false; },

                async handleFormSubmit(event) {
                    event.preventDefault();
                    if (this.isSubmitting) return;
                    this.isSubmitting = true;

                    const form = event.target;
                    const formData = new FormData(form);
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;

                    try {
                        submitBtn.innerHTML = `<svg class="animate-spin h-4 w-4 text-white inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating...`;
                        submitBtn.disabled = true;

                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        if (response.ok) {
                            const contentDisposition = response.headers.get('Content-Disposition');
                            let filename = 'report.pdf';
                            if (contentDisposition) {
                                const match = contentDisposition.match(/filename="(.+)"/);
                                if (match) filename = match[1];
                            }

                            const blob = await response.blob();
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.style.display = 'none';
                            a.href = url;
                            a.download = filename;
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                            document.body.removeChild(a);

                            this.showToast('PDF generated successfully', 'success');

                            setTimeout(() => {
                                this.closeOrgModal();
                                this.closeMemberModal();
                                this.closeActivityModal();
                                window.location.reload();
                            }, 1000);
                        } else {
                            const errorData = await response.json();
                            this.showToast(errorData.message || 'Error generating PDF.', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.showToast('Network error. Please try again.', 'error');
                    } finally {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                        this.isSubmitting = false;
                    }
                },

                showToast(message, type) {
                    const bg = type === 'success' ? 'bg-gray-900' : 'bg-red-600';
                    const el = document.createElement('div');
                    el.className = `fixed top-4 right-4 ${bg} text-white px-5 py-3 text-sm z-[100] transform transition-transform duration-200 translate-x-full`;
                    el.textContent = message;
                    document.body.appendChild(el);
                    setTimeout(() => el.classList.remove('translate-x-full'), 50);
                    setTimeout(() => {
                        el.classList.add('translate-x-full');
                        setTimeout(() => document.body.removeChild(el), 200);
                    }, 3000);
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-dashboard-layout>
