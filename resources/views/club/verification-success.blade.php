<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verified - Club Registration</title>
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
                    <p class="text-green-200 mt-1">Email Verification Successful</p>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-lg p-8">
        <!-- Success Icon -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                <i class="fas fa-check-circle text-green-600 text-4xl"></i>
            </div>
            <h2 class="text-2xl font-semibold text-gray-900 mb-2">
                @if($alreadyVerified)
                    Email Already Verified
                @else
                    Email Verified Successfully!
                @endif
            </h2>
            <p class="text-gray-600">{{ $officer->email }}</p>
        </div>

        <!-- Redirect Message -->
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-center">
            <p class="text-sm text-gray-700">
                <i class="fas fa-spinner fa-spin text-green-600 mr-2"></i>
                <span id="message">
                    @if($alreadyVerified)
                        Redirecting to registration...
                    @else
                        Great! Now let's continue with your registration...
                    @endif
                </span>
            </p>
        </div>

        <!-- Manual Link (fallback) -->
        <div class="text-center">
            <p class="text-sm text-gray-600 mb-4">If you are not redirected automatically:</p>
            <a href="{{ route('club.register', ['officer_id' => $officer->id]) }}" 
               class="inline-flex items-center justify-center bg-gradient-to-r from-[#29553c] to-[#031a0a] hover:from-[#1e3d2c] hover:to-[#000000] text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-arrow-right mr-2"></i>
                Continue to Registration
            </a>
        </div>
    </div>

    <script>
        // Store verification status in localStorage so waiting tab can detect it
        localStorage.setItem('email_verified_{{ $officer->id }}', 'true');
        localStorage.setItem('verification_timestamp', Date.now().toString());

        // Try to close this tab if it was opened from the verification waiting page
        function attemptClose() {
            // Try to close the window (works if opened by window.open or is a popup)
            window.close();
            
            // If window didn't close (still here after 500ms), redirect instead
            setTimeout(function() {
                if (!document.hidden) {
                    window.location.href = '{{ route("club.register", ["officer_id" => $officer->id]) }}';
                }
            }, 500);
        }

        // Wait a moment to show the success message, then redirect/close
        setTimeout(attemptClose, 2000);

        // Also notify any open tabs using BroadcastChannel if supported
        if ('BroadcastChannel' in window) {
            const channel = new BroadcastChannel('email_verification');
            channel.postMessage({
                type: 'verified',
                officer_id: '{{ $officer->id }}',
                timestamp: Date.now()
            });
            channel.close();
        }

        // Fallback: Update message after a moment
        setTimeout(function() {
            document.getElementById('message').innerHTML = 
                'This tab will close automatically. If not, click the button below.';
        }, 3000);
    </script>
</body>
</html>
