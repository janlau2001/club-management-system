<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Club Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen py-8 px-4">
    <!-- Header -->
    <header class="bg-gradient-to-r from-[#29553c] to-[#031a0a] shadow-lg mb-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-6">
                <div>
                    <h1 class="text-2xl font-bold text-white">Register Your Club</h1>
                    <p class="text-green-200 mt-1">Step 1 of 3: Gmail Address</p>
                </div>
                <a href="{{ route('club.login') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Login
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-lg p-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-semibold text-gray-900">Email Registration</h2>
            <p class="text-sm text-gray-600 mt-2">Create your account using Gmail to register your club</p>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex">
                    <i class="fas fa-exclamation-circle text-red-600 mr-2 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-semibold text-red-800 mb-1">Please correct the following errors:</p>
                        <ul class="text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('club.email-registration.store') }}">
            @csrf

            <!-- Gmail Address -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="email">
                    <i class="fas fa-envelope mr-1"></i> Gmail Address <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                    placeholder="yourname@gmail.com" required>
                <p class="text-xs text-gray-500 mt-1">Use your Gmail address (@gmail.com)</p>
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="password">
                    <i class="fas fa-lock mr-1"></i> Password <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password" id="password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                    placeholder="Enter your password" required>
                <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="password_confirmation">
                    <i class="fas fa-lock mr-1"></i> Confirm Password <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                    placeholder="Re-enter your password" required>
            </div>

            <!-- Info Box -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mr-2 mt-0.5"></i>
                    <p class="text-xs text-gray-700">
                        <strong>Important:</strong> After clicking "Continue", a verification email will be sent to your Gmail address. You must verify your email before proceeding with the registration.
                    </p>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200">
                <i class="fas fa-arrow-right mr-2"></i> Continue to Email Verification
            </button>
        </form>

        <!-- Divider -->
        <div class="mt-8 mb-8 flex items-center">
            <div class="flex-1 border-t border-gray-300"></div>
            <span class="px-4 text-sm font-medium text-gray-500">OR</span>
            <div class="flex-1 border-t border-gray-300"></div>
        </div>

        <!-- Google OAuth Button -->
        <a href="{{ route('club.auth.google') }}" 
           class="w-full bg-white hover:bg-gray-50 border-2 border-gray-300 hover:border-gray-400 text-gray-700 font-semibold py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center shadow-sm">
            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Sign in with Google
        </a>
        <div class="text-center mt-3">
            <p class="text-xs text-gray-500">
                <i class="fas fa-shield-alt text-green-500 mr-1"></i>
                Secure authentication using your Google account
            </p>
        </div>

        <!-- Back to Login -->
        <div class="mt-8 pt-6 border-t border-gray-200 text-center">
            <p class="text-sm text-gray-600">
                Already have an account? 
                <a href="{{ route('club.login') }}" class="text-green-600 hover:text-green-800 font-semibold transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i>Login here
                </a>
            </p>
        </div>
    </div>
</body>
</html>
