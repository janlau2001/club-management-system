<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Submit Appeal - {{ $club->name }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="p-4 bg-gradient-to-r from-[#29553c] to-[#031a0a] shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">Submit Violation Appeal</h1>
                    <p class="text-white opacity-90">{{ $club->name }} • {{ $club->department }}</p>
                </div>
                <a href="{{ route('club.officer.violations') }}" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-colors">
                    ← Back to Violations
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="p-6">
            <div class="max-w-4xl mx-auto">
                <!-- Violation Info Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Violation Details</h2>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Title:</span>
                            <p class="text-gray-900 font-medium">{{ $violation->title }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Description:</span>
                            <p class="text-gray-700">{{ $violation->description }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Date:</span>
                                <span class="text-gray-900">{{ $violation->violation_date->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Points:</span>
                                <span class="text-gray-900 font-medium">{{ $violation->points }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Severity:</span>
                                @php
                                    $severityColors = [
                                        'minor' => 'bg-yellow-100 text-yellow-800',
                                        'moderate' => 'bg-orange-100 text-orange-800',
                                        'major' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $severityColors[$violation->severity] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($violation->severity) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Indicator (if club is suspended and has multiple violations) -->
                @if($club->status === 'suspended' && $confirmedViolationsCount > 1)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-yellow-900 mb-1">Multiple Violations Require Appeals</h3>
                                <p class="text-xs text-yellow-800">
                                    Your club currently has <strong>{{ $confirmedViolationsCount }} confirmed violations</strong>. 
                                    All violations must be appealed and approved by the Head Office before your club's suspension can be lifted.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($club->status === 'suspended' && $confirmedViolationsCount === 1)
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-blue-900 mb-1">Last Violation to Appeal</h3>
                                <p class="text-xs text-blue-800">
                                    This is your club's last confirmed violation. If this appeal is approved, your club's suspension will be lifted.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Appeal Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Appeal Information</h2>
                    
                    <form action="{{ route('club.officer.submit-appeal') }}" method="POST" enctype="multipart/form-data" id="appealForm">
                        @csrf
                        <input type="hidden" name="violation_id" value="{{ $violation->id }}">
                        
                        <div class="space-y-6">
                            <!-- Name Field (Read-only) -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ $clubUser->name }}" 
                                    readonly
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                >
                                <p class="text-xs text-gray-500 mt-1">Automatically filled from your account</p>
                            </div>

                            <!-- Position Field (Read-only) -->
                            <div>
                                <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                                    Position in Club <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="position" 
                                    name="position" 
                                    value="{{ ucfirst($clubUser->role) }}" 
                                    readonly
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                >
                                <p class="text-xs text-gray-500 mt-1">Your current role in the club</p>
                            </div>

                            <!-- Appeal Description -->
                            <div>
                                <label for="appeal_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                    Appeal Description <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    id="appeal_reason" 
                                    name="appeal_reason" 
                                    rows="6" 
                                    required
                                    maxlength="2000"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"
                                    placeholder="Explain why you are appealing this violation. Provide detailed information about the circumstances, any misunderstandings, or evidence that supports your appeal."
                                >{{ old('appeal_reason') }}</textarea>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-xs text-gray-500">Provide a detailed explanation for your appeal</p>
                                    <span id="charCount" class="text-xs text-gray-500">0 / 2000</span>
                                </div>
                                @error('appeal_reason')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- File Upload -->
                            <div>
                                <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">
                                    Supporting Document (Optional)
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-500 transition-colors">
                                    <input 
                                        type="file" 
                                        id="attachment" 
                                        name="attachment" 
                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                        class="hidden"
                                        onchange="updateFileName(this)"
                                    >
                                    <label for="attachment" class="cursor-pointer">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600">
                                            <span class="font-medium text-green-600 hover:text-green-500">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, JPG, JPEG, PNG (Max. 5MB)</p>
                                    </label>
                                    <p id="fileName" class="text-sm text-gray-700 mt-2 hidden"></p>
                                </div>
                                @error('attachment')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Important Notice -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Important Information</h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <ul class="list-disc list-inside space-y-1">
                                                <li>Your appeal will be reviewed by the Head Office</li>
                                                <li>The review process may take 3-5 business days</li>
                                                <li>You will be notified of the decision via email</li>
                                                <li>Supporting documents can strengthen your appeal</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end gap-4 pt-4">
                                <a 
                                    href="{{ route('club.officer.violations') }}" 
                                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                                >
                                    Cancel
                                </a>
                                <button 
                                    type="submit" 
                                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2"
                                    id="submitBtn"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Submit Appeal
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Character counter
        const textarea = document.getElementById('appeal_reason');
        const charCount = document.getElementById('charCount');
        
        textarea.addEventListener('input', function() {
            charCount.textContent = `${this.value.length} / 2000`;
        });

        // File name display
        function updateFileName(input) {
            const fileName = document.getElementById('fileName');
            if (input.files && input.files[0]) {
                fileName.textContent = `Selected: ${input.files[0].name}`;
                fileName.classList.remove('hidden');
            } else {
                fileName.classList.add('hidden');
            }
        }

        // Form submission handling
        document.getElementById('appealForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Submitting...
            `;
        });
    </script>
</body>
</html>
