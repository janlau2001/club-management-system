<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In - Club Management</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen font-sans flex items-center justify-center p-4">
    <!-- Main Container -->
    <div class="w-full max-w-5xl backdrop-blur-md rounded-2xl shadow-2xl overflow-hidden border border-gray-200 transition-all duration-700 bg-gradient-to-br from-[#29553c] to-[#031a0a]">
        <div class="flex min-h-[600px]">
            <!-- Left Side - Content/Forms -->
            <div class="flex-1 flex items-center justify-center p-8 relative overflow-hidden">
                <!-- Background Decorative Elements -->
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute -top-20 -left-20 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-20 -right-20 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                </div>

                <!-- Admin Welcome Text -->
                <div class="max-w-sm text-center text-white relative z-10">
                    <div class="mb-6">
                        <div class="w-24 h-24 mx-auto mb-4 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    </div>

                    <h1 class="text-3xl font-bold mb-3">ADMIN</h1>
                    <h2 class="text-2xl font-bold mb-4">PORTAL</h2>
                    <p class="text-sm opacity-90 leading-relaxed">
                        Administrative access for faculty and staff. Manage organizations, review applications, and oversee club activities.
                    </p>
                </div>


            </div>

            <!-- Right Side - Admin Login Form -->
            <div class="flex-1 bg-black/20 backdrop-blur-sm flex items-center justify-center p-8 relative">
                <div class="w-full max-w-sm">
                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf
                        <input type="hidden" name="user_type" value="admin">

                        <div class="text-center mb-6">
                            <h2 class="text-2xl font-bold text-white mb-1">ADMIN LOGIN</h2>
                        </div>

                        @if(session('success'))
                            <div class="bg-green-500/20 border border-green-500/50 text-white px-4 py-3 rounded-lg text-sm mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="bg-red-500/20 border border-red-500/50 text-white px-4 py-3 rounded-lg text-sm">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <div class="relative">
                            <input id="admin_email" name="email" type="email" required
                                   value="{{ old('email') }}"
                                   placeholder="Admin Email"
                                   class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent backdrop-blur-sm text-sm">
                        </div>

                        <div class="relative">
                            <input id="admin_password" name="password" type="password" required
                                   placeholder="Password"
                                   class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent backdrop-blur-sm text-sm">
                        </div>

                        <button type="submit" class="w-full bg-white/20 hover:bg-white/30 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/20 text-sm">
                            Login as Admin
                        </button>

                        <div class="text-center">
                            <a href="/" class="text-white/80 hover:text-white text-xs transition-colors">
                                ← Back to Club Login
                            </a>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>
</body>
</html>










