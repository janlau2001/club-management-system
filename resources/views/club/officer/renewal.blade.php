<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Club Renewal - {{ $club->name }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="p-4 bg-gradient-to-r from-[#29553c] to-[#031a0a]">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">Club Renewal</h1>
                    <p class="text-white opacity-90">{{ $club->name }} • {{ $club->department }}</p>
                    <p class="text-white opacity-75 text-sm">{{ $clubUser->name }} ({{ $clubUser->position ?? 'Officer' }})</p>
                </div>
                <a href="{{ route('club.officer.dashboard') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 transition-colors">
                    ← Back to Dashboard
                </a>
            </div>
        </header>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3">
                {{ session('error') }}
            </div>
        @endif

        <main class="p-6">
            <div class="max-w-4xl mx-auto">
                
                <!-- Renewal Form -->
                <div class="bg-white border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">Club Renewal Application</h2>
                        <p class="text-gray-600 mt-1">Submit your club renewal for the current academic year</p>
                    </div>

                    <form method="POST" action="{{ route('club.officer.renewal.submit') }}" enctype="multipart/form-data" class="p-6 space-y-6">
                        @csrf

                        <!-- Error Messages -->
                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-2">Academic Year *</label>
                                <select id="academic_year" name="academic_year" required
                                        class="w-full px-3 py-2 border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                                    <option value="">Select Academic Year</option>
                                    <option value="2024-2025" {{ old('academic_year') == '2024-2025' ? 'selected' : '' }}>2024-2025</option>
                                    <option value="2025-2026" {{ old('academic_year') == '2025-2026' ? 'selected' : '' }}>2025-2026</option>
                                    <option value="2026-2027" {{ old('academic_year') == '2026-2027' ? 'selected' : '' }}>2026-2027</option>
                                </select>
                            </div>

                            <div>
                                <label for="last_renewal_date" class="block text-sm font-medium text-gray-700 mb-2">Last Date of Renewal</label>
                                <input type="date" id="last_renewal_date" name="last_renewal_date" 
                                       value="{{ old('last_renewal_date', $lastRenewalDate ?? '') }}" readonly
                                       class="w-full px-3 py-2 border border-gray-300 bg-gray-50 text-gray-600">
                                <p class="text-xs text-gray-500 mt-1">Automatically generated based on previous renewal</p>
                            </div>

                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                                <input type="text" id="department" name="department" 
                                       value="{{ $club->department }}" readonly
                                       class="w-full px-3 py-2 border border-gray-300 bg-gray-50 text-gray-600">
                            </div>

                            <div>
                                <label for="nature" class="block text-sm font-medium text-gray-700 mb-2">Nature of Organization *</label>
                                <select id="nature" name="nature" required
                                        class="w-full px-3 py-2 border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                                    <option value="">Select Nature</option>
                                    <option value="Academic" {{ old('nature', $club->club_type) == 'Academic' ? 'selected' : '' }}>Academic</option>
                                    <option value="Interest" {{ old('nature', $club->club_type) == 'Interest' ? 'selected' : '' }}>Interest</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="faculty_adviser" class="block text-sm font-medium text-gray-700 mb-2">Name of Faculty Adviser *</label>
                            <input type="text" id="faculty_adviser" name="faculty_adviser" 
                                   value="{{ old('faculty_adviser', $club->adviser_name ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                        </div>

                        <div>
                            <label for="rationale" class="block text-sm font-medium text-gray-700 mb-2">Brief Rationale of the Club *</label>
                            <textarea id="rationale" name="rationale" rows="4" required
                                      class="w-full px-3 py-2 border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors"
                                      placeholder="Explain the purpose and continued relevance of your club...">{{ old('rationale', $club->description ?? '') }}</textarea>
                        </div>

                        <!-- Required Documents -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Required Documents</h3>
                            <p class="text-sm text-gray-600">Please upload the following documents (PDF or Word format, max 10MB each):</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="officers_list_file" class="block text-sm font-medium text-gray-700 mb-2">
                                        List of New Officers *
                                        <span class="text-xs text-gray-500">(duly certified by the adviser)</span>
                                    </label>
                                    <input type="file" id="officers_list_file" name="officers_list_file" required
                                           accept=".pdf,.doc,.docx"
                                           class="w-full px-3 py-2 border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                                </div>

                                <div>
                                    <label for="activities_plan_file" class="block text-sm font-medium text-gray-700 mb-2">
                                        Planned Program of Activities *
                                        <span class="text-xs text-gray-500">(signed by the adviser)</span>
                                    </label>
                                    <input type="file" id="activities_plan_file" name="activities_plan_file" required
                                           accept=".pdf,.doc,.docx"
                                           class="w-full px-3 py-2 border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                                </div>

                                <div>
                                    <label for="budget_proposal_file" class="block text-sm font-medium text-gray-700 mb-2">
                                        Budget Proposal *
                                        <span class="text-xs text-gray-500">(signed by treasurer, president, and adviser)</span>
                                    </label>
                                    <input type="file" id="budget_proposal_file" name="budget_proposal_file" required
                                           accept=".pdf,.doc,.docx"
                                           class="w-full px-3 py-2 border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                                </div>

                                <div>
                                    <label for="constitution_file" class="block text-sm font-medium text-gray-700 mb-2">
                                        Updated Constitution and By-laws *
                                        <span class="text-xs text-gray-500">(signed by president and adviser)</span>
                                    </label>
                                    <input type="file" id="constitution_file" name="constitution_file" required
                                           accept=".pdf,.doc,.docx"
                                           class="w-full px-3 py-2 border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                                </div>
                            </div>
                        </div>

                        <!-- Club Information Display -->
                        <div class="bg-gray-50 p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Club Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Club Name:</span>
                                    <span class="text-gray-900">{{ $club->name }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Department:</span>
                                    <span class="text-gray-900">{{ $club->department }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Current Status:</span>
                                    <span class="text-gray-900">{{ ucfirst($club->status) }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Member Count:</span>
                                    <span class="text-gray-900">{{ $club->member_count ?? 0 }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Date Registered:</span>
                                    <span class="text-gray-900">{{ $club->date_registered ? $club->date_registered->format('M j, Y') : 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Submitting Officer:</span>
                                    <span class="text-gray-900">{{ $clubUser->name }} ({{ $clubUser->position ?? 'Officer' }})</span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('club.officer.dashboard') }}" 
                               class="px-6 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-gray-900 hover:bg-gray-800 transition-colors text-white font-medium">
                                Submit Renewal Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
