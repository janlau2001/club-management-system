<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Club Management</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen font-sans flex items-center justify-center p-4">
    <!-- Main Container -->
    <div class="w-full max-w-4xl backdrop-blur-md rounded-2xl shadow-2xl overflow-hidden border border-gray-200 bg-gradient-to-br from-[#29553c] to-[#031a0a]">
        <div class="flex min-h-[700px]">
            <!-- Left Side - Welcome Content -->
            <div class="flex-1 flex items-center justify-center p-8 relative overflow-hidden">
                <!-- Background Decorative Elements -->
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute -top-20 -left-20 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-20 -right-20 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                </div>

                <div class="max-w-sm text-center text-white relative z-10">
                    <div class="mb-6">
                        <div class="w-24 h-24 mx-auto mb-4 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                    </div>

                    <h1 class="text-3xl font-bold mb-3">JOIN YOUR</h1>
                    <h2 class="text-2xl font-bold mb-4">STUDENT CLUB</h2>
                    <p class="text-sm opacity-90 leading-relaxed">
                        Register as a club officer and start managing your student organization. Connect with fellow students and build lasting memories.
                    </p>
                </div>
            </div>

            <!-- Right Side - Registration Form -->
            <div class="flex-1 bg-black/20 backdrop-blur-sm flex items-center justify-center p-8 relative">
                <div class="w-full max-w-sm">
                    <form method="POST" action="{{ route('register') }}" class="space-y-5" @submit="validateForm($event)">
                        @csrf

                        <div class="text-center mb-6">
                            <h2 class="text-2xl font-bold text-white mb-1">OFFICER REGISTRATION</h2>
                        </div>

                        <div class="space-y-4" x-data="passwordValidator()">
                            <div class="relative">
                                <input id="officer_name" name="name" type="text" required
                                       placeholder="Full Name"
                                       class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent backdrop-blur-sm text-sm">
                            </div>

                            <div class="relative">
                                <input id="username" name="username" type="text" required
                                       placeholder="Preferred Username"
                                       class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent backdrop-blur-sm text-sm">
                            </div>

                            <div class="relative">
                                <select id="department" name="department" required
                                        class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent backdrop-blur-sm text-sm">
                                    <option value="" class="text-gray-900">Select Department</option>
                                    <option value="SASTE" class="text-gray-900">SASTE</option>
                                    <option value="SNAHS" class="text-gray-900">SNAHS</option>
                                    <option value="SITE" class="text-gray-900">SITE</option>
                                    <option value="SBAHM" class="text-gray-900">SBAHM</option>
                                    <option value="BEU" class="text-gray-900">BEU</option>
                                    <option value="SOM" class="text-gray-900">SOM</option>
                                    <option value="GRADUATE SCHOOL" class="text-gray-900">GRADUATE SCHOOL</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-white/90">Club Status</label>
                                <div class="space-y-2">
                                    <label class="flex items-center text-white/80 text-sm">
                                        <input type="radio" name="club_status" value="registered"
                                               class="mr-2 text-blue-600 bg-white/10 border-white/20 focus:ring-blue-500">
                                        Currently registered to a club
                                    </label>
                                    <label class="flex items-center text-white/80 text-sm">
                                        <input type="radio" name="club_status" value="renew"
                                               class="mr-2 text-blue-600 bg-white/10 border-white/20 focus:ring-blue-500">
                                        Will renew an ongoing club
                                    </label>
                                </div>
                            </div>

                            <div class="relative">
                                <input id="student_email" name="email" type="email" required
                                       placeholder="Student Email"
                                       class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent backdrop-blur-sm text-sm">
                            </div>

                            <div class="relative">
                                <input id="officer_password" name="password" type="password" required
                                       x-model="password"
                                       @input="validatePassword()"
                                       @focus="showRequirements = true"
                                       @blur="showRequirements = false"
                                       placeholder="Password"
                                       class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent backdrop-blur-sm text-sm">

                                <!-- Password Requirements Checklist -->
                                <div class="mt-2 space-y-1 text-xs" x-show="showRequirements && password.length > 0"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95">
                                    <div class="flex items-center" :class="requirements.length ? 'text-green-400' : 'text-red-400'">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" x-show="requirements.length"></path>
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" x-show="!requirements.length"></path>
                                        </svg>
                                        <span>At least 8 characters</span>
                                    </div>
                                    <div class="flex items-center" :class="requirements.uppercase ? 'text-green-400' : 'text-red-400'">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" x-show="requirements.uppercase"></path>
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" x-show="!requirements.uppercase"></path>
                                        </svg>
                                        <span>At least 1 uppercase letter</span>
                                    </div>
                                    <div class="flex items-center" :class="requirements.lowercase ? 'text-green-400' : 'text-red-400'">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" x-show="requirements.lowercase"></path>
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" x-show="!requirements.lowercase"></path>
                                        </svg>
                                        <span>At least 1 lowercase letter</span>
                                    </div>
                                    <div class="flex items-center" :class="requirements.number ? 'text-green-400' : 'text-red-400'">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" x-show="requirements.number"></path>
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" x-show="!requirements.number"></path>
                                        </svg>
                                        <span>At least 1 number</span>
                                    </div>
                                </div>
                            </div>

                            <div class="relative">
                                <input id="officer_password_confirmation" name="password_confirmation" type="password" required
                                       x-model="confirmPassword"
                                       @input="checkPasswordMatch()"
                                       placeholder="Confirm Password"
                                       class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent backdrop-blur-sm text-sm">

                                <!-- Password Match Indicator -->
                                <div class="mt-1 text-xs" x-show="confirmPassword.length > 0">
                                    <div class="flex items-center" :class="passwordMatch ? 'text-green-400' : 'text-red-400'">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" x-show="passwordMatch"></path>
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" x-show="!passwordMatch"></path>
                                        </svg>
                                        <span x-text="passwordMatch ? 'Passwords match' : 'Passwords do not match'"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="relative">
                                <select id="year_level" name="year_level" required
                                        class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent backdrop-blur-sm text-sm">
                                    <option value="" class="text-gray-900">Select Year Level</option>
                                    <option value="1st Year" class="text-gray-900">1st Year</option>
                                    <option value="2nd Year" class="text-gray-900">2nd Year</option>
                                    <option value="3rd Year" class="text-gray-900">3rd Year</option>
                                    <option value="4th Year" class="text-gray-900">4th Year</option>
                                    <option value="5th Year" class="text-gray-900">5th Year</option>
                                    <option value="Graduate" class="text-gray-900">Graduate</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-white/20 hover:bg-white/30 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/20 text-sm">
                            Register as Officer
                        </button>

                        <div class="text-center">
                            <p class="text-white/80 text-xs">
                                Already have an account?
                                <a href="{{ route('login') }}" class="text-white hover:underline font-medium">Login Here</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
function passwordValidator() {
    return {
        password: '',
        confirmPassword: '',
        showRequirements: false,
        passwordMatch: false,
        requirements: {
            length: false,
            uppercase: false,
            lowercase: false,
            number: false
        },
        validatePassword() {
            this.requirements.length = this.password.length >= 8;
            this.requirements.uppercase = /[A-Z]/.test(this.password);
            this.requirements.lowercase = /[a-z]/.test(this.password);
            this.requirements.number = /\d/.test(this.password);
            this.checkPasswordMatch();
        },
        checkPasswordMatch() {
            this.passwordMatch = this.confirmPassword === this.password && this.confirmPassword.length > 0;
        },
        validateForm(event) {
            // Check if passwords match
            if (this.password !== this.confirmPassword || this.confirmPassword.length === 0) {
                event.preventDefault();
                alert('Passwords do not match. Please make sure both password fields are identical.');
                return false;
            }

            // Check if password meets requirements
            if (!this.requirements.length || !this.requirements.uppercase || !this.requirements.lowercase || !this.requirements.number) {
                event.preventDefault();
                alert('Password must contain at least 8 characters, 1 uppercase letter, 1 lowercase letter, and 1 number.');
                return false;
            }

            // All validations passed, allow form submission
            return true;
        }
    }
}
</script>







