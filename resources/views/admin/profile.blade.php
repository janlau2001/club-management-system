<x-dashboard-layout title="Admin Profile">
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center">
                    <span class="text-2xl font-bold text-white">{{ strtoupper(substr($admin->email, 0, 1)) }}</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $admin->name }}</h1>
                    <p class="text-lg text-gray-600">{{ $roleName }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Profile Information</h2>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                                    <p class="text-gray-900">{{ $admin->name }}</p>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                                    <p class="text-gray-900">{{ $admin->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-gray-900 font-medium">{{ $roleName }}</p>
                                        <p class="text-sm text-gray-500">Administrative Access</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Account Created</label>
                            <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <p class="text-gray-900">{{ $admin->created_at->format('F j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Change Password Section -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Change Password</h2>
                    
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-green-800 font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    @foreach($errors->all() as $error)
                                        <p class="text-red-800 text-sm">{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.change-password') }}" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                            <input type="password" 
                                   id="current_password" 
                                   name="current_password"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                   placeholder="Enter current password"
                                   required>
                        </div>

                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input type="password"
                                   id="new_password"
                                   name="new_password"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                   placeholder="Enter new password"
                                   minlength="6"
                                   oninput="validatePassword()"
                                   required>
                            <div class="mt-2 space-y-1">
                                <div class="flex items-center text-xs">
                                    <span id="length-check" class="w-4 h-4 mr-2 rounded-full bg-gray-300 flex items-center justify-center">
                                        <svg class="w-2 h-2 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                    <span id="length-text" class="text-gray-500">At least 6 characters</span>
                                </div>
                                <div class="flex items-center text-xs">
                                    <span id="uppercase-check" class="w-4 h-4 mr-2 rounded-full bg-gray-300 flex items-center justify-center">
                                        <svg class="w-2 h-2 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                    <span id="uppercase-text" class="text-gray-500">At least 1 uppercase letter</span>
                                </div>
                                <div class="flex items-center text-xs">
                                    <span id="number-check" class="w-4 h-4 mr-2 rounded-full bg-gray-300 flex items-center justify-center">
                                        <svg class="w-2 h-2 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                    <span id="number-text" class="text-gray-500">At least 1 number</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <input type="password"
                                   id="new_password_confirmation"
                                   name="new_password_confirmation"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                   placeholder="Confirm new password"
                                   oninput="validatePasswordMatch()"
                                   required>
                            <div class="mt-2">
                                <div class="flex items-center text-xs">
                                    <span id="match-check" class="w-4 h-4 mr-2 rounded-full bg-gray-300 flex items-center justify-center">
                                        <svg class="w-2 h-2 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                    <span id="match-text" class="text-gray-500">Passwords match</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                                id="submit-button"
                                class="w-full bg-gray-400 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2 cursor-not-allowed"
                                disabled>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span>Change Password</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Security Information -->
        <div class="mt-8">
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Security Information</h3>
                        <div class="text-blue-800 space-y-2">
                            <p>• Your password is used to verify sensitive actions like approving registrations and managing clubs.</p>
                            <p>• Password must contain at least 6 characters, 1 uppercase letter, and 1 number.</p>
                            <p>• Your session will automatically expire after 30 minutes of inactivity.</p>
                            <p>• Always log out when finished to protect your account.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let passwordRequirements = {
            length: false,
            uppercase: false,
            number: false,
            match: false
        };

        function validatePassword() {
            const password = document.getElementById('new_password').value;

            // Check length requirement
            const lengthValid = password.length >= 6;
            updateRequirement('length', lengthValid);
            passwordRequirements.length = lengthValid;

            // Check uppercase requirement
            const uppercaseValid = /[A-Z]/.test(password);
            updateRequirement('uppercase', uppercaseValid);
            passwordRequirements.uppercase = uppercaseValid;

            // Check number requirement
            const numberValid = /\d/.test(password);
            updateRequirement('number', numberValid);
            passwordRequirements.number = numberValid;

            // Re-validate password match if confirmation field has content
            const confirmPassword = document.getElementById('new_password_confirmation').value;
            if (confirmPassword) {
                validatePasswordMatch();
            }

            updateSubmitButton();
        }

        function validatePasswordMatch() {
            const password = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('new_password_confirmation').value;

            const matchValid = password === confirmPassword && password.length > 0;
            updateRequirement('match', matchValid);
            passwordRequirements.match = matchValid;

            updateSubmitButton();
        }

        function updateRequirement(type, isValid) {
            const checkElement = document.getElementById(type + '-check');
            const textElement = document.getElementById(type + '-text');
            const svgElement = checkElement.querySelector('svg');

            if (isValid) {
                checkElement.classList.remove('bg-gray-300');
                checkElement.classList.add('bg-green-500');
                textElement.classList.remove('text-gray-500');
                textElement.classList.add('text-green-600');
                svgElement.classList.remove('hidden');
            } else {
                checkElement.classList.remove('bg-green-500');
                checkElement.classList.add('bg-gray-300');
                textElement.classList.remove('text-green-600');
                textElement.classList.add('text-gray-500');
                svgElement.classList.add('hidden');
            }
        }

        function updateSubmitButton() {
            const submitButton = document.getElementById('submit-button');
            const currentPassword = document.getElementById('current_password').value;

            const allRequirementsMet = passwordRequirements.length &&
                                     passwordRequirements.uppercase &&
                                     passwordRequirements.number &&
                                     passwordRequirements.match &&
                                     currentPassword.length > 0;

            if (allRequirementsMet) {
                submitButton.disabled = false;
                submitButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
                submitButton.classList.add('bg-green-600', 'hover:bg-green-700');
            } else {
                submitButton.disabled = true;
                submitButton.classList.remove('bg-green-600', 'hover:bg-green-700');
                submitButton.classList.add('bg-gray-400', 'cursor-not-allowed');
            }
        }

        // Add event listener for current password field
        document.getElementById('current_password').addEventListener('input', updateSubmitButton);
    </script>
</x-dashboard-layout>
