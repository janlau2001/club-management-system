<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Club Registration System</title>
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
                    <p class="text-green-200 mt-1">Step 1 of 3: Email Verification</p>
                </div>
                <a href="{{ route('club.login') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Login
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-lg p-8">
        <!-- Email Icon -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                <i class="fas fa-envelope text-green-600 text-3xl"></i>
            </div>
            <h2 class="text-2xl font-semibold text-gray-900">Verify Your Gmail Address</h2>
            @if(isset($officer))
                <p class="text-sm text-gray-600 mt-2">{{ $officer->email }}</p>
            @endif
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Info Message -->
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-gray-700 text-center">
                <i class="fas fa-info-circle text-blue-600 mr-1"></i>
                We've sent a verification link to your Gmail address. Please check your inbox and click the link to continue with your club registration.
            </p>
        </div>

        <!-- Resend Button -->
        @if(isset($officer))
            <form method="POST" action="{{ route('club.verification.resend') }}">
                @csrf
                <input type="hidden" name="officer_id" value="{{ $officer->id }}">
                <button type="submit" class="w-full bg-gradient-to-r from-[#29553c] to-[#031a0a] hover:from-[#1e3d2c] hover:to-[#000000] text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Resend Verification Email
                </button>
            </form>
        @endif

        <!-- Additional Info -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>
                Didn't receive the email? Check your spam/junk folder or click the button above to resend.
            </p>
        </div>
    </div>

    <!-- Auto-check verification status script -->
    <script>
        @if(isset($officer))
        const officerId = '{{ $officer->id }}';
        let checkInterval;

        // Listen for verification via BroadcastChannel (modern browsers)
        if ('BroadcastChannel' in window) {
            const channel = new BroadcastChannel('email_verification');
            channel.onmessage = function(event) {
                if (event.data.type === 'verified' && event.data.officer_id === officerId) {
                    clearInterval(checkInterval);
                    channel.close();
                    window.location.href = '{{ route("club.register", ["officer_id" => $officer->id]) }}';
                }
            };
        }

        // Also check localStorage for verification (fallback for older browsers)
        function checkLocalStorage() {
            const verified = localStorage.getItem('email_verified_' + officerId);
            const timestamp = localStorage.getItem('verification_timestamp');
            
            if (verified === 'true' && timestamp) {
                const timeDiff = Date.now() - parseInt(timestamp);
                // If verified within last 60 seconds
                if (timeDiff < 60000) {
                    clearInterval(checkInterval);
                    localStorage.removeItem('email_verified_' + officerId);
                    localStorage.removeItem('verification_timestamp');
                    window.location.href = '{{ route("club.register", ["officer_id" => $officer->id]) }}';
                }
            }
        }

        // Check verification status via API every 5 seconds
        function checkVerificationStatus() {
            fetch('{{ route("club.verification.check-status", $officer->id) }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.verified) {
                    clearInterval(checkInterval);
                    window.location.href = '{{ route("club.register", ["officer_id" => $officer->id]) }}';
                }
            })
            .catch(error => {
                console.log('Status check error:', error);
            });
        }

        // Check localStorage immediately and every 2 seconds
        checkLocalStorage();
        setInterval(checkLocalStorage, 2000);

        // Also check via API every 5 seconds as backup
        checkInterval = setInterval(checkVerificationStatus, 5000);

        // Clean up interval when leaving page
        window.addEventListener('beforeunload', function() {
            clearInterval(checkInterval);
        });
        @endif
    </script>
</body>
</html>
