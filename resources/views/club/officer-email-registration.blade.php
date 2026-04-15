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
                    <p class="text-green-200 mt-1">Step 1 of 3: Email Address</p>
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
            <p class="text-sm text-gray-600 mt-2">Create your account using Gmail or Yahoo to register your club</p>
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

            <!-- Email Address -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="email">
                    <i class="fas fa-envelope mr-1"></i> Email Address <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                    placeholder="yourname@gmail.com or yourname@yahoo.com" required>
                <p class="text-xs text-gray-500 mt-1">Use your Gmail (@gmail.com) or Yahoo (@yahoo.com) address</p>
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
                        <strong>Important:</strong> After clicking "Continue", a verification email will be sent to your email address. You must verify your email within 3 minutes before proceeding with the registration.
                    </p>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200">
                <i class="fas fa-arrow-right mr-2"></i> Continue to Email Verification
            </button>
        </form>

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
