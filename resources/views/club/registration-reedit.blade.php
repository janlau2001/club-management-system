<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Re-edit Registration - Club Management</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen font-sans">
    <!-- Header -->
    <header class="bg-gradient-to-r from-[#29553c] to-[#031a0a] shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-6">
                <div>
                    <h1 class="text-2xl font-bold text-white">Re-edit Registration</h1>
                    <p class="text-green-200 mt-1">Update and resubmit your club registration</p>
                </div>
                <div class="text-white text-sm">
                    <span class="opacity-75">Officer:</span> <strong>{{ $officer->name }}</strong>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Link -->
        <div class="mb-6">
            <a href="javascript:history.back()" 
               class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Registration Tracker
            </a>
        </div>

        <!-- Rejection Notice -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Registration was rejected</h3>
                    @if($registration->rejection_reason)
                        <div class="mt-2 text-sm text-red-700">
                            <strong>Reason:</strong> {{ $registration->rejection_reason }}
                        </div>
                    @endif
                    @if($registration->rejected_by)
                        <div class="mt-1 text-sm text-red-700">
                            <strong>Rejected by:</strong> {{ $registration->rejected_by }}
                        </div>
                    @endif
                    <div class="mt-2 text-sm text-red-600">
                        Please address the issues mentioned above and resubmit your application.
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Update Registration Information</h2>
                    <p class="text-gray-600 mt-1">Make necessary changes and resubmit your application.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('club.registration-reedit.update', $registration) }}" enctype="multipart/form-data" class="p-6 space-y-8" autocomplete="off">
                @csrf
                @method('PUT')

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
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

                <!-- Club Information -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Club Information</h3>
                    
                    <div>
                        <label for="club_name" class="block text-sm font-medium text-gray-700 mb-2">1. Name of Club/Organization *</label>
                        <input type="text" id="club_name" name="club_name" value="{{ old('club_name', $registration->club_name) }}" required
                               autocomplete="off"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                    </div>

                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-2">2. The School Department *</label>
                        <input type="text" id="department" name="department" value="{{ old('department', $registration->department) }}" required
                               autocomplete="off"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                    </div>

                    <div>
                        <label for="nature" class="block text-sm font-medium text-gray-700 mb-2">3. Nature of Club/Organization *</label>
                        <select id="nature" name="nature" required
                                autocomplete="off"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                            <option value="">Select Nature</option>
                            <option value="Academic" {{ old('nature', $registration->nature) == 'Academic' ? 'selected' : '' }}>Academic</option>
                            <option value="Interest" {{ old('nature', $registration->nature) == 'Interest' ? 'selected' : '' }}>Interest</option>
                        </select>
                    </div>

                    <div>
                        <label for="rationale" class="block text-sm font-medium text-gray-700 mb-2">4. Rationale for the Formation of the Club/Organization *</label>
                        <textarea id="rationale" name="rationale" rows="4" required
                                  autocomplete="off"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                  placeholder="Explain the purpose and goals of your club/organization...">{{ old('rationale', $registration->rationale) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Minimum 100 characters required</p>
                    </div>

                    <div>
                        <label for="recommended_adviser" class="block text-sm font-medium text-gray-700 mb-2">5. Recommended Adviser *</label>
                        <input type="text" id="recommended_adviser" name="recommended_adviser" value="{{ old('recommended_adviser', $registration->recommended_adviser) }}" required
                               placeholder="Full name of the recommended faculty adviser"
                               autocomplete="off"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                    </div>
                </div>

                <!-- Document Requirements -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Document Requirements</h3>
                    <p class="text-sm text-gray-600">You can upload new documents to replace existing ones or keep the current files.</p>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Constitution File -->
                        <div>
                            <label for="constitution_file" class="block text-sm font-medium text-gray-700 mb-2">
                                1. Constitution and By-laws
                            </label>
                            @if($registration->constitution_file)
                                <div class="mb-2 p-2 bg-green-50 border border-green-200 rounded text-sm text-green-700">
                                    Current file: {{ basename($registration->constitution_file) }}
                                </div>
                            @endif
                            <input type="file" id="constitution_file" name="constitution_file" 
                                   accept=".pdf,.doc,.docx"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                            <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, DOC, DOCX (Max: 10MB)</p>
                        </div>

                        <!-- Officers List File -->
                        <div>
                            <label for="officers_list_file" class="block text-sm font-medium text-gray-700 mb-2">
                                2. Complete List of Officers
                            </label>
                            @if($registration->officers_list_file)
                                <div class="mb-2 p-2 bg-green-50 border border-green-200 rounded text-sm text-green-700">
                                    Current file: {{ basename($registration->officers_list_file) }}
                                </div>
                            @endif
                            <input type="file" id="officers_list_file" name="officers_list_file" 
                                   accept=".pdf,.doc,.docx"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                            <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, DOC, DOCX (Max: 10MB)</p>
                        </div>

                        <!-- Activities Plan File -->
                        <div>
                            <label for="activities_plan_file" class="block text-sm font-medium text-gray-700 mb-2">
                                3. Proposed Activities/Plan
                            </label>
                            @if($registration->activities_plan_file)
                                <div class="mb-2 p-2 bg-green-50 border border-green-200 rounded text-sm text-green-700">
                                    Current file: {{ basename($registration->activities_plan_file) }}
                                </div>
                            @endif
                            <input type="file" id="activities_plan_file" name="activities_plan_file" 
                                   accept=".pdf,.doc,.docx"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                            <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, DOC, DOCX (Max: 10MB)</p>
                        </div>

                        <!-- Budget Proposal File -->
                        <div>
                            <label for="budget_proposal_file" class="block text-sm font-medium text-gray-700 mb-2">
                                4. Budget Proposal
                            </label>
                            @if($registration->budget_proposal_file)
                                <div class="mb-2 p-2 bg-green-50 border border-green-200 rounded text-sm text-green-700">
                                    Current file: {{ basename($registration->budget_proposal_file) }}
                                </div>
                            @endif
                            <input type="file" id="budget_proposal_file" name="budget_proposal_file" 
                                   accept=".pdf,.doc,.docx"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                            <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, DOC, DOCX (Max: 10MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="javascript:history.back()" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-8 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors font-medium">
                        Update & Resubmit Application
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
