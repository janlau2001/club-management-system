<x-dashboard-layout>
    <x-slot name="title">Reports</x-slot>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Reports</h1>
            <p class="text-gray-600 mt-2">Generate and view comprehensive system reports</p>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <strong>Validation Errors:</strong>
            </div>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Report Categories -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8" x-data="reportManager()">
        <!-- Organization Reports -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-4">
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">Organization Reports</h3>
            </div>
            <p class="text-gray-600 text-sm mb-4">Detailed reports on all registered organizations, their status, and activities.</p>
            <button @click="openOrgModal()" class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Generate Report
            </button>
        </div>

        <!-- Membership Reports -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-4">
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">Membership Reports</h3>
            </div>
            <p class="text-gray-600 text-sm mb-4">Comprehensive member statistics, demographics, and engagement metrics.</p>
            <button @click="openMemberModal()" class="w-full bg-green-50 hover:bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Generate Report
            </button>
        </div>

        <!-- Activity Reports -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-4">
                <div class="bg-orange-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">Activity Reports</h3>
            </div>
            <p class="text-gray-600 text-sm mb-4">Track registrations, renewals, and system activity over time.</p>
            <button @click="openActivityModal()" class="w-full bg-orange-50 hover:bg-orange-100 text-orange-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Generate Report
            </button>
        </div>

        <!-- Organization Report Modal -->
        <div x-show="showOrgModal" x-cloak class="fixed inset-0 bg-gray-600 bg-opacity-40 z-50 flex items-center justify-center p-4">
            <div @click.away="closeOrgModal()" class="bg-white rounded-xl max-w-lg w-full shadow-xl border border-gray-200">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 rounded-t-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <h3 class="text-lg font-semibold">Organization Report</h3>
                        </div>
                        <button @click="closeOrgModal()" class="text-white hover:text-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <form method="POST" action="{{ route('dashboard.reports.organizations') }}" @submit="handleFormSubmit($event)">
                    @csrf
                    <!-- Modal Body -->
                    <div class="p-6 space-y-5">
                        <!-- Report Type Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Report Scope</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative">
                                    <input type="radio" name="report_type" value="all" x-model="orgReportType" class="sr-only">
                                    <div class="border-2 border-gray-200 rounded-lg p-4 cursor-pointer transition-all"
                                         :class="orgReportType === 'all' ? 'border-blue-500 bg-blue-50' : 'hover:border-gray-300'">
                                        <div class="flex items-center justify-center mb-2">
                                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <div class="font-medium text-gray-900">All Organizations</div>
                                            <div class="text-xs text-gray-500 mt-1">Complete system report</div>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="relative">
                                    <input type="radio" name="report_type" value="specific" x-model="orgReportType" class="sr-only">
                                    <div class="border-2 border-gray-200 rounded-lg p-4 cursor-pointer transition-all"
                                         :class="orgReportType === 'specific' ? 'border-blue-500 bg-blue-50' : 'hover:border-gray-300'">
                                        <div class="flex items-center justify-center mb-2">
                                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <div class="font-medium text-gray-900">Specific Organization</div>
                                            <div class="text-xs text-gray-500 mt-1">Single organization focus</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Organizations Preview (All) -->
                        <div x-show="orgReportType === 'all'" x-cloak class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="font-medium text-blue-900">Report Preview</span>
                            </div>
                            <div class="max-h-32 overflow-y-auto bg-white border border-blue-200 rounded p-3 mb-3">
                                <div class="text-sm space-y-2">
                                    @foreach(\App\Models\Club::orderBy('name')->take(5)->get() as $club)
                                        <div class="flex items-center justify-between py-1">
                                            <span class="font-medium text-gray-900">{{ $club->name }}</span>
                                            <span class="text-xs text-blue-600 px-2 py-1 bg-blue-100 rounded">{{ $club->department }}</span>
                                        </div>
                                    @endforeach
                                    @if(\App\Models\Club::count() > 5)
                                        <div class="text-center text-xs text-gray-500 italic pt-2 border-t border-gray-200">
                                            ... and {{ \App\Models\Club::count() - 5 }} more organizations
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center justify-center">
                                <span class="text-sm font-medium text-blue-800 bg-blue-100 px-3 py-1 rounded-full">
                                    {{ \App\Models\Club::count() }} organizations will be included
                                </span>
                            </div>
                        </div>
                        
                        <!-- Specific Organization Selection -->
                        <div x-show="orgReportType === 'specific'" x-cloak>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Select Organization</label>
                            <select name="club_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                <option value="">Choose an organization...</option>
                                @foreach(\App\Models\Club::orderBy('name')->get() as $club)
                                    <option value="{{ $club->id }}">{{ $club->name }} ({{ $club->department }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Security Section -->
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span class="font-medium text-red-900">Security Verification</span>
                            </div>
                            <input type="password" name="admin_password" required 
                                   class="w-full px-4 py-3 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   placeholder="Enter your admin password">
                            <p class="text-xs text-red-600 mt-2">Authentication required for report generation</p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex items-center justify-between">
                        <button type="button" @click="closeOrgModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex items-center space-x-2 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Generate PDF</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Membership Report Modal -->
        <div x-show="showMemberModal" x-cloak class="fixed inset-0 bg-gray-600 bg-opacity-40 z-50 flex items-center justify-center p-4">
            <div @click.away="closeMemberModal()" class="bg-white rounded-xl max-w-lg w-full shadow-xl border border-gray-200">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 rounded-t-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold">Membership Report</h3>
                        </div>
                        <button @click="closeMemberModal()" class="text-white hover:text-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <form method="POST" action="{{ route('dashboard.reports.members') }}" @submit="handleFormSubmit($event)">
                    @csrf
                    <!-- Modal Body -->
                    <div class="p-6 space-y-5">
                        <!-- Report Type Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Report Scope</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative">
                                    <input type="radio" name="report_type" value="all" x-model="memberReportType" class="sr-only">
                                    <div class="border-2 border-gray-200 rounded-lg p-4 cursor-pointer transition-all"
                                         :class="memberReportType === 'all' ? 'border-green-500 bg-green-50' : 'hover:border-gray-300'">
                                        <div class="flex items-center justify-center mb-2">
                                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <div class="font-medium text-gray-900">All Organizations</div>
                                            <div class="text-xs text-gray-500 mt-1">Complete membership data</div>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="relative">
                                    <input type="radio" name="report_type" value="specific" x-model="memberReportType" class="sr-only">
                                    <div class="border-2 border-gray-200 rounded-lg p-4 cursor-pointer transition-all"
                                         :class="memberReportType === 'specific' ? 'border-green-500 bg-green-50' : 'hover:border-gray-300'">
                                        <div class="flex items-center justify-center mb-2">
                                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <div class="font-medium text-gray-900">Specific Organization</div>
                                            <div class="text-xs text-gray-500 mt-1">Single organization members</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Organizations Preview (All) -->
                        <div x-show="memberReportType === 'all'" x-cloak class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="font-medium text-green-900">Membership Preview</span>
                            </div>
                            <div class="max-h-32 overflow-y-auto bg-white border border-green-200 rounded p-3 mb-3">
                                <div class="text-sm space-y-2">
                                    @foreach(\App\Models\Club::orderBy('name')->take(5)->get() as $club)
                                        <div class="flex items-center justify-between py-1">
                                            <span class="font-medium text-gray-900">{{ $club->name }}</span>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs text-green-600 font-medium">{{ $club->member_count }} members</span>
                                                <span class="text-xs text-gray-500 px-2 py-1 bg-gray-100 rounded">{{ $club->department }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if(\App\Models\Club::count() > 5)
                                        <div class="text-center text-xs text-gray-500 italic pt-2 border-t border-gray-200">
                                            ... and {{ \App\Models\Club::count() - 5 }} more organizations
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center justify-center">
                                <span class="text-sm font-medium text-green-800 bg-green-100 px-3 py-1 rounded-full">
                                    {{ \App\Models\Club::count() }} organizations • {{ \App\Models\ClubUser::count() }} total members
                                </span>
                            </div>
                        </div>
                        
                        <!-- Specific Organization Selection -->
                        <div x-show="memberReportType === 'specific'" x-cloak>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Select Organization</label>
                            <select name="club_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                                <option value="">Choose an organization...</option>
                                @foreach(\App\Models\Club::orderBy('name')->get() as $club)
                                    <option value="{{ $club->id }}">{{ $club->name }} ({{ $club->member_count }} members)</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Security Section -->
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span class="font-medium text-red-900">Security Verification</span>
                            </div>
                            <input type="password" name="admin_password" required 
                                   class="w-full px-4 py-3 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   placeholder="Enter your admin password">
                            <p class="text-xs text-red-600 mt-2">Authentication required for report generation</p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex items-center justify-between">
                        <button type="button" @click="closeMemberModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium flex items-center space-x-2 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Generate PDF</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Activity Report Modal -->
        <div x-show="showActivityModal" x-cloak class="fixed inset-0 bg-gray-600 bg-opacity-40 z-50 flex items-center justify-center p-4">
            <div @click.away="closeActivityModal()" class="bg-white rounded-xl max-w-lg w-full shadow-xl border border-gray-200">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white px-6 py-4 rounded-t-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold">Activity Report</h3>
                        </div>
                        <button @click="closeActivityModal()" class="text-white hover:text-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <form method="POST" action="{{ route('dashboard.reports.activities') }}" @submit="handleFormSubmit($event)">
                    @csrf
                    <!-- Modal Body -->
                    <div class="p-6 space-y-5">
                        <!-- Time Period Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Time Period</label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="relative">
                                    <input type="radio" name="time_period" value="last_day" x-model="activityPeriod" class="sr-only">
                                    <div class="border-2 border-gray-200 rounded-lg p-4 cursor-pointer transition-all text-center"
                                         :class="activityPeriod === 'last_day' ? 'border-orange-500 bg-orange-50' : 'hover:border-gray-300'">
                                        <div class="font-medium text-gray-900">Last Day</div>
                                        <div class="text-xs text-gray-500 mt-1">24 hours</div>
                                    </div>
                                </label>
                                
                                <label class="relative">
                                    <input type="radio" name="time_period" value="last_week" x-model="activityPeriod" class="sr-only">
                                    <div class="border-2 border-gray-200 rounded-lg p-4 cursor-pointer transition-all text-center"
                                         :class="activityPeriod === 'last_week' ? 'border-orange-500 bg-orange-50' : 'hover:border-gray-300'">
                                        <div class="font-medium text-gray-900">Last Week</div>
                                        <div class="text-xs text-gray-500 mt-1">7 days</div>
                                    </div>
                                </label>
                                
                                <label class="relative">
                                    <input type="radio" name="time_period" value="last_month" x-model="activityPeriod" class="sr-only">
                                    <div class="border-2 border-gray-200 rounded-lg p-4 cursor-pointer transition-all text-center"
                                         :class="activityPeriod === 'last_month' ? 'border-orange-500 bg-orange-50' : 'hover:border-gray-300'">
                                        <div class="font-medium text-gray-900">Last Month</div>
                                        <div class="text-xs text-gray-500 mt-1">30 days</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Activity Preview -->
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                <span class="font-medium text-orange-900">Activity Summary</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-white border border-orange-200 rounded p-3 text-center">
                                    <div class="text-sm text-gray-600">Recent Activities</div>
                                    <div class="text-lg font-bold text-orange-700">
                                        {{ \App\Models\ClubRegistrationRequest::where('created_at', '>=', now()->subDays(30))->count() + \App\Models\ClubRenewal::where('created_at', '>=', now()->subDays(30))->count() }}
                                    </div>
                                </div>
                                <div class="bg-white border border-orange-200 rounded p-3 text-center">
                                    <div class="text-sm text-gray-600">New Members</div>
                                    <div class="text-lg font-bold text-orange-700">
                                        {{ \App\Models\ClubUser::where('created_at', '>=', now()->subDays(30))->count() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Security Section -->
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span class="font-medium text-red-900">Security Verification</span>
                            </div>
                            <input type="password" name="admin_password" required 
                                   class="w-full px-4 py-3 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   placeholder="Enter your admin password">
                            <p class="text-xs text-red-600 mt-2">Authentication required for report generation</p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex items-center justify-between">
                        <button type="button" @click="closeActivityModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium flex items-center space-x-2 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Generate PDF</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-md">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Organizations</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ \App\Models\Club::count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-md">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Members</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ \App\Models\ClubUser::count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 bg-purple-100 rounded-md">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Active Clubs</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ \App\Models\Club::where('status', 'active')->count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 bg-orange-100 rounded-md">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Recent Activities</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ \App\Models\ClubRegistrationRequest::where('created_at', '>=', now()->subDays(30))->count() + \App\Models\ClubRenewal::where('created_at', '>=', now()->subDays(30))->count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Script for Modal Management -->
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
                    this.orgReportType = 'all'; // Reset to default
                    // Clear password field
                    setTimeout(() => {
                        const passwordField = document.querySelector('form[action*="organizations"] input[name="admin_password"]');
                        if (passwordField) passwordField.value = '';
                    }, 100);
                },
                closeOrgModal() {
                    this.showOrgModal = false;
                    this.isSubmitting = false;
                },
                
                openMemberModal() {
                    this.showMemberModal = true;
                    this.memberReportType = 'all'; // Reset to default
                    // Clear password field
                    setTimeout(() => {
                        const passwordField = document.querySelector('form[action*="members"] input[name="admin_password"]');
                        if (passwordField) passwordField.value = '';
                    }, 100);
                },
                closeMemberModal() {
                    this.showMemberModal = false;
                    this.isSubmitting = false;
                },
                
                openActivityModal() {
                    this.showActivityModal = true;
                    this.activityPeriod = 'last_month'; // Reset to default
                    // Clear password field
                    setTimeout(() => {
                        const passwordField = document.querySelector('form[action*="activities"] input[name="admin_password"]');
                        if (passwordField) passwordField.value = '';
                    }, 100);
                },
                closeActivityModal() {
                    this.showActivityModal = false;
                    this.isSubmitting = false;
                },

                async handleFormSubmit(event) {
                    event.preventDefault();
                    
                    if (this.isSubmitting) return;
                    this.isSubmitting = true;

                    const form = event.target;
                    const formData = new FormData(form);
                    
                    try {
                        // Show loading state
                        const submitButton = form.querySelector('button[type="submit"]');
                        const originalText = submitButton.innerHTML;
                        submitButton.innerHTML = `
                            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Generating...
                        `;
                        submitButton.disabled = true;

                        // Submit the form with fetch
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        if (response.ok) {
                            // Get the content disposition header to extract filename
                            const contentDisposition = response.headers.get('Content-Disposition');
                            let filename = 'report.pdf';
                            if (contentDisposition) {
                                const filenameMatch = contentDisposition.match(/filename="(.+)"/);
                                if (filenameMatch) {
                                    filename = filenameMatch[1];
                                }
                            }

                            // Create blob and download
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

                            // Success feedback
                            this.showSuccessMessage('PDF generated successfully!');
                            
                            // Close modal and refresh page after a short delay
                            setTimeout(() => {
                                this.closeOrgModal();
                                this.closeMemberModal();
                                this.closeActivityModal();
                                
                                // Refresh the page to clear forms and reset state
                                window.location.reload();
                            }, 1000);

                        } else {
                            // Handle error response
                            const errorData = await response.json();
                            this.showErrorMessage(errorData.message || 'Error generating PDF. Please try again.');
                        }

                    } catch (error) {
                        console.error('Error:', error);
                        this.showErrorMessage('Network error. Please check your connection and try again.');
                    } finally {
                        // Reset button state
                        const submitButton = form.querySelector('button[type="submit"]');
                        submitButton.innerHTML = originalText;
                        submitButton.disabled = false;
                        this.isSubmitting = false;
                    }
                },

                showSuccessMessage(message) {
                    // Create and show success notification
                    const notification = document.createElement('div');
                    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-transform duration-300 translate-x-full';
                    notification.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            ${message}
                        </div>
                    `;
                    document.body.appendChild(notification);
                    
                    // Animate in
                    setTimeout(() => notification.classList.remove('translate-x-full'), 100);
                    
                    // Remove after 3 seconds
                    setTimeout(() => {
                        notification.classList.add('translate-x-full');
                        setTimeout(() => document.body.removeChild(notification), 300);
                    }, 3000);
                },

                showErrorMessage(message) {
                    // Create and show error notification
                    const notification = document.createElement('div');
                    notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-transform duration-300 translate-x-full';
                    notification.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            ${message}
                        </div>
                    `;
                    document.body.appendChild(notification);
                    
                    // Animate in
                    setTimeout(() => notification.classList.remove('translate-x-full'), 100);
                    
                    // Remove after 5 seconds
                    setTimeout(() => {
                        notification.classList.add('translate-x-full');
                        setTimeout(() => document.body.removeChild(notification), 300);
                    }, 5000);
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-dashboard-layout>