<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Summary - Club Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #29553c 0%, #031a0a 100%);
        }

        .gradient-button {
            background: linear-gradient(135deg, #29553c 0%, #1a3d2a 100%);
            transition: all 0.3s ease;
        }

        .gradient-button:hover {
            background: linear-gradient(135deg, #1a3d2a 0%, #031a0a 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .success-checkmark {
            animation: scaleIn 0.6s ease-out;
        }

        @keyframes scaleIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl w-full">
            <!-- Success Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden fade-in">
                <!-- Header with Gradient -->
                <div class="gradient-bg px-8 py-12 text-center">
                    <!-- Success Checkmark -->
                    <div class="success-checkmark mb-6 flex justify-center">
                        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl font-bold text-white mb-3">Registration Submitted Successfully!</h1>
                    <p class="text-white/90 text-lg">Your club registration is now under review by the administration.</p>
                </div>

                <!-- Body Content -->
                <div class="px-8 py-10">
                    <!-- Summary Section -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Registration Summary</h2>
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-6 space-y-3">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">Officer Name</p>
                                    <p class="font-semibold text-gray-900">{{ $officer->name }}</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">Email Address</p>
                                    <p class="font-semibold text-gray-900">{{ $officer->email }}</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">Club/Organization Name</p>
                                    <p class="font-semibold text-gray-900">{{ $clubName }}</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">Submission Date</p>
                                    <p class="font-semibold text-gray-900">{{ now()->format('F d, Y - h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Next Steps Section -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">What Happens Next?</h2>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-full flex items-center justify-center font-semibold text-sm">
                                    1
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Dean Endorsement</h3>
                                    <p class="text-gray-600 text-sm">Your registration will be reviewed and endorsed by the Dean.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-full flex items-center justify-center font-semibold text-sm">
                                    2
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">PSG Council Approval</h3>
                                    <p class="text-gray-600 text-sm">After dean endorsement, it will be reviewed by the PSG Council.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-full flex items-center justify-center font-semibold text-sm">
                                    3
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Director Noting</h3>
                                    <p class="text-gray-600 text-sm">The Director will review and note your registration.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-full flex items-center justify-center font-semibold text-sm">
                                    4
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Vice President Approval</h3>
                                    <p class="text-gray-600 text-sm">Final approval will be given by the Vice President.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-1">Track Your Registration</h4>
                                <p class="text-blue-800 text-sm">You can track the status of your registration at any time using the Registration Tracker. We'll notify you via email once your registration is approved or if any action is needed.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('club.registration-tracker') }}" 
                           class="flex-1 gradient-button text-white text-center py-4 px-6 rounded-xl font-semibold flex items-center justify-center space-x-2 shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <span>View Registration Tracker</span>
                        </a>

                        <a href="{{ route('club.login') }}" 
                           class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 text-center py-4 px-6 rounded-xl font-semibold flex items-center justify-center space-x-2 transition-all duration-300 hover:shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Back to Login Page</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="text-center mt-8">
                <p class="text-gray-600 text-sm">
                    Need help? Contact the Office of Student Affairs for assistance.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
