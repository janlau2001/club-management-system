<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Club Registration - Club Management</title>
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
                    <h1 class="text-2xl font-bold text-white">Club Registration</h1>
                    <p class="text-green-200 mt-1">Step 2: Register Your Club/Organization</p>
                </div>
                <div class="text-white text-sm">
                    <span class="opacity-75">Officer:</span> <strong>{{ $officer->name }}</strong>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center space-x-4">
                    <!-- Step 1 - Complete -->
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                            ✓
                        </div>
                        <span class="ml-2 text-sm font-medium text-green-600">Officer Registration</span>
                    </div>
                    
                    <!-- Connector -->
                    <div class="w-16 h-1 bg-green-600 rounded"></div>
                    
                    <!-- Step 2 - Active -->
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                            2
                        </div>
                        <span class="ml-2 text-sm font-medium text-green-600">Club Registration</span>
                    </div>
                    
                    <!-- Connector -->
                    <div class="w-16 h-1 bg-gray-300 rounded"></div>
                    
                    <!-- Step 3 - Inactive -->
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-sm font-medium">
                            3
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Admin Approval</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Application for Recognition of New Club/Organization</h2>
                        <p class="text-gray-600 mt-1">Please provide complete information about your club/organization.</p>
                    </div>
                    <button type="button" onclick="goBackToOfficerRegistration()" 
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Back to Officer Registration</span>
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('club.club-registration.store', $officer) }}" enctype="multipart/form-data" class="p-6 space-y-8" autocomplete="off">
                @csrf

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
                        <input type="text" id="club_name" name="club_name" value="{{ old('club_name') }}" required
                               autocomplete="off"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                    </div>

                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-2">2. The School Department *</label>
                        <input type="text" id="department" name="department" value="{{ $officer->department }}" readonly
                               autocomplete="off"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                        <p class="text-xs text-gray-500 mt-1">Based on your officer registration</p>
                    </div>

                    <div>
                        <label for="nature" class="block text-sm font-medium text-gray-700 mb-2">3. Nature of Club/Organization *</label>
                        <select id="nature" name="nature" required
                                autocomplete="off"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                            <option value="">Select Nature</option>
                            <option value="Academic" {{ old('nature') == 'Academic' ? 'selected' : '' }}>Academic</option>
                            <option value="Interest" {{ old('nature') == 'Interest' ? 'selected' : '' }}>Interest</option>
                        </select>
                    </div>

                    <div>
                        <label for="rationale" class="block text-sm font-medium text-gray-700 mb-2">4. Rationale for the Formation of the Club/Organization *</label>
                        <textarea id="rationale" name="rationale" rows="4" required
                                  autocomplete="off"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                  placeholder="Explain the purpose and goals of your club/organization...">{{ old('rationale') }}</textarea>
                    </div>

                    <div>
                        <label for="recommended_adviser" class="block text-sm font-medium text-gray-700 mb-2">5. Recommended Adviser *</label>
                        <input type="text" id="recommended_adviser" name="recommended_adviser" value="{{ old('recommended_adviser') }}" required
                               placeholder="Full name of the recommended faculty adviser"
                               autocomplete="off"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                    </div>
                </div>

                <!-- Required Documents -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Required Documents</h3>
                    <p class="text-sm text-gray-600">Please upload the following documents (PDF, DOC, or DOCX format, max 10MB each):</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="constitution_file" class="block text-sm font-medium text-gray-700 mb-2">Constitution and By-Laws *</label>
                            <input type="file" id="constitution_file" name="constitution_file" required
                                   accept=".pdf,.doc,.docx"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                        </div>

                        <div>
                            <label for="officers_list_file" class="block text-sm font-medium text-gray-700 mb-2">List of Officers *</label>
                            <input type="file" id="officers_list_file" name="officers_list_file" required
                                   accept=".pdf,.doc,.docx"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                        </div>

                        <div>
                            <label for="activities_plan_file" class="block text-sm font-medium text-gray-700 mb-2">Activities/Action Plan *</label>
                            <input type="file" id="activities_plan_file" name="activities_plan_file" required
                                   accept=".pdf,.doc,.docx"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                        </div>

                        <div>
                            <label for="budget_proposal_file" class="block text-sm font-medium text-gray-700 mb-2">Budget Proposal *</label>
                            <input type="file" id="budget_proposal_file" name="budget_proposal_file" required
                                   accept=".pdf,.doc,.docx"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                        </div>
                    </div>
                </div>

                <!-- Officer Information Display -->
                <!-- Removed Primary Officer Information section -->

                <!-- Submit Button -->
                <div class="flex justify-between space-x-4 pt-6 border-t border-gray-200">
                    <form method="POST" action="{{ route('club.club-registration.cancel', $officer) }}">
                        @csrf
                        <button type="submit" 
                                class="px-6 py-2 bg-red-100 hover:bg-red-200 text-red-700 hover:text-red-800 border border-red-300 hover:border-red-400 rounded-lg transition-all duration-200">
                            Cancel
                        </button>
                    </form>
                    <button type="submit" 
                            class="px-6 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 font-medium">
                        Submit Club Registration
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Hidden form for going back with officer data preserved -->
    <form id="backToOfficerForm" method="GET" action="{{ route('club.register') }}" style="display: none;">
        <input type="hidden" name="first_name" value="{{ $officer->first_name }}">
        <input type="hidden" name="last_name" value="{{ $officer->last_name }}">
        <input type="hidden" name="suffix" value="{{ $officer->suffix ?? '' }}">
        <input type="hidden" name="phone" value="{{ $officer->phone }}">
        <input type="hidden" name="student_id" value="{{ $officer->student_id }}">
        <input type="hidden" name="year_level" value="{{ $officer->year_level }}">
        <input type="hidden" name="course" value="{{ $officer->course }}">
        <input type="hidden" name="department" value="{{ $officer->department }}">
        <input type="hidden" name="position" value="{{ $officer->position }}">
        <input type="hidden" name="edit_mode" value="1">
        <input type="hidden" name="officer_id" value="{{ $officer->id }}">
    </form>

    <script>
        function goBackToOfficerRegistration() {
            // Store current club form data in localStorage
            const clubFormData = {
                club_name: document.getElementById('club_name')?.value || '',
                nature: document.getElementById('nature')?.value || '',
                rationale: document.getElementById('rationale')?.value || '',
                recommended_adviser: document.getElementById('recommended_adviser')?.value || '',
            };
            
            localStorage.setItem('clubFormData', JSON.stringify(clubFormData));
            
            // Submit the hidden form to go back with officer data
            document.getElementById('backToOfficerForm').submit();
        }

        // Restore club form data when coming back from officer registration
        document.addEventListener('DOMContentLoaded', function() {
            // Additional autocomplete prevention
            disableAutocomplete();
            
            const savedClubData = localStorage.getItem('clubFormData');
            if (savedClubData) {
                try {
                    const data = JSON.parse(savedClubData);
                    
                    // Restore form values
                    if (data.club_name && document.getElementById('club_name')) {
                        document.getElementById('club_name').value = data.club_name;
                    }
                    if (data.nature && document.getElementById('nature')) {
                        document.getElementById('nature').value = data.nature;
                    }
                    if (data.rationale && document.getElementById('rationale')) {
                        document.getElementById('rationale').value = data.rationale;
                    }
                    if (data.recommended_adviser && document.getElementById('recommended_adviser')) {
                        document.getElementById('recommended_adviser').value = data.recommended_adviser;
                    }
                } catch (e) {
                    console.log('Error restoring form data:', e);
                }
                
                // Clear the stored data
                localStorage.removeItem('clubFormData');
            }
        });

        function disableAutocomplete() {
            // Get all input and select elements
            const formElements = document.querySelectorAll('input, select, textarea');
            
            formElements.forEach(function(element) {
                // Skip readonly, hidden, and file elements
                if (!element.readOnly && element.type !== 'hidden' && element.type !== 'file') {
                    element.setAttribute('autocomplete', 'off');
                    element.setAttribute('autocapitalize', 'off');
                    element.setAttribute('autocorrect', 'off');
                    element.setAttribute('spellcheck', 'false');
                    
                    // Add event listeners to prevent autocomplete
                    element.addEventListener('focus', function() {
                        this.setAttribute('autocomplete', 'new-password');
                    });
                    
                    element.addEventListener('blur', function() {
                        this.setAttribute('autocomplete', 'off');
                    });
                }
            });
            
            // Clear any stored browser form data
            if (window.history && window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        }
    </script>

    <!-- Credentials Modal -->
    @if(session('registration_credentials'))
    @php session()->forget('registration_credentials'); @endphp
    <div id="credentialsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-data="credentialsModal()">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 p-6">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Your Login Credentials</h3>
                <p class="text-gray-600 text-sm mt-2">Save these credentials safely - they will be used to track your application status</p>
            </div>

            <div class="space-y-4 mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="flex items-center justify-between">
                        <code class="text-sm font-mono bg-white px-3 py-2 rounded border flex-1 mr-2">{{ session('registration_credentials.email') }}</code>
                        <button @click="copyToClipboard('{{ session('registration_credentials.email') }}')" 
                                class="px-3 py-2 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 transition-colors">
                            Copy
                        </button>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="flex items-center justify-between">
                        <code class="text-sm font-mono bg-white px-3 py-2 rounded border flex-1 mr-2" x-text="showPassword ? '{{ session('registration_credentials.password') }}' : '••••••••••••'"></code>
                        <div class="flex space-x-1">
                            <button @click="togglePassword()" 
                                    class="px-3 py-2 bg-gray-600 text-white rounded text-xs hover:bg-gray-700 transition-colors">
                                <span x-text="showPassword ? 'Hide' : 'Show'"></span>
                            </button>
                            <button @click="copyToClipboard('{{ session('registration_credentials.password') }}')" 
                                    class="px-3 py-2 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 transition-colors">
                                Copy
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Countdown Timer -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-red-800 font-medium">Auto-close in: <span x-text="timeLeft" class="font-bold text-red-900"></span> seconds</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-3">
                <button @click="closeModal()" 
                        class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium">
                    Close Now
                </button>
                <button @click="downloadCredentials()" 
                        class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                    Download
                </button>
            </div>
        </div>
    </div>

    <script>
        function credentialsModal() {
            return {
                showPassword: false,
                timeLeft: 30,
                interval: null,

                init() {
                    this.startCountdown();
                },

                startCountdown() {
                    this.interval = setInterval(() => {
                        this.timeLeft--;
                        if (this.timeLeft <= 0) {
                            this.closeModal();
                        }
                    }, 1000);
                },

                togglePassword() {
                    this.showPassword = !this.showPassword;
                },

                copyToClipboard(text) {
                    navigator.clipboard.writeText(text).then(() => {
                        // Show brief success feedback
                        const button = event.target;
                        const originalText = button.textContent;
                        button.textContent = 'Copied!';
                        button.classList.add('bg-green-600');
                        setTimeout(() => {
                            button.textContent = originalText;
                            button.classList.remove('bg-green-600');
                        }, 1000);
                    });
                },

                downloadCredentials() {
                    const credentials = `Club Registration Credentials\n\nEmail: {{ session('registration_credentials.email') }}\nPassword: {{ session('registration_credentials.password') }}\n\nPlease keep these credentials safe for tracking your application status.`;
                    const blob = new Blob([credentials], { type: 'text/plain' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'club-credentials.txt';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                },

                closeModal() {
                    if (this.interval) {
                        clearInterval(this.interval);
                    }
                    document.getElementById('credentialsModal').style.display = 'none';
                }
            }
        }
    </script>
    @endif

    <!-- Cancel Confirmation Modal -->
    <div id="cancelConfirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 9999;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="mt-2 px-7 py-3">
                    <h3 class="text-lg font-medium text-gray-900 text-center">Cancel Registration</h3>
                    <p class="text-sm text-gray-600 mt-2 text-center">
                        Are you sure you want to cancel the registration? This will delete all entered information.
                    </p>
                    <div class="flex items-center justify-center space-x-3 mt-4">
                        <button type="button" onclick="hideCancelConfirmModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            No, Keep It
                        </button>
                        <button type="button" onclick="confirmCancel()" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Yes, Cancel Registration
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let cancelForm = null;

        function showCancelConfirmModal(event) {
            event.preventDefault();
            cancelForm = event.target;
            document.getElementById('cancelConfirmModal').classList.remove('hidden');
            return false;
        }

        function hideCancelConfirmModal() {
            document.getElementById('cancelConfirmModal').classList.add('hidden');
            cancelForm = null;
        }

        function confirmCancel() {
            if (cancelForm) {
                cancelForm.onsubmit = null; // Remove the event handler
                cancelForm.submit();
            }
        }

        // Attach event handler on page load
        document.addEventListener('DOMContentLoaded', function() {
            const cancelForms = document.querySelectorAll('form[action*="club-registration.cancel"]');
            cancelForms.forEach(form => {
                form.onsubmit = showCancelConfirmModal;
            });
        });
    </script>
</body>
</html>
