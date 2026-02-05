<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login | MaidGlow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cream: '#FDFBF7',
                        ink: '#1a1a1a',
                        sage: {
                            50: '#f6f7f6',
                            100: '#e3e7e3',
                            200: '#c7d0c7',
                            300: '#a3b1a3',
                            400: '#7d8f7d',
                            500: '#5f7360',
                            600: '#4a5b4b',
                        }
                    },
                    fontFamily: {
                        serif: ['Instrument Serif', 'Georgia', 'serif'],
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .texture-overlay {
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            opacity: 0.03;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-cream text-ink antialiased min-h-screen flex flex-col">
    <div class="fixed inset-0 texture-overlay"></div>

    <header class="relative border-b border-sage-200/60">
        <div class="max-w-md mx-auto px-6">
            <div class="flex items-center justify-center h-20">
                <a href="{{ route('booking.calculator') }}" class="font-serif text-2xl tracking-tight">MaidGlow</a>
            </div>
        </div>
    </header>

    <main class="relative flex-1 flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">
            <div class="text-center mb-10">
                <h1 class="font-serif text-3xl mb-2">Staff portal</h1>
                <p class="text-sage-500">Sign in to access the dashboard</p>
            </div>

            <!-- Demo credentials -->
            <div class="mb-8 bg-sage-50 border border-sage-200 rounded-xl p-4 space-y-2">
                <p class="text-xs font-medium text-sage-500 uppercase tracking-wide">Demo accounts</p>
                <div class="grid grid-cols-2 gap-4 text-sm text-sage-600">
                    <button type="button" onclick="fillCredentials('admin@maidtoglow.com')" class="text-left hover:text-ink transition-colors">
                        <p class="font-medium text-ink">Admin</p>
                        <p>admin@maidtoglow.com</p>
                    </button>
                    <button type="button" onclick="fillCredentials('maria@maidtoglow.com')" class="text-left hover:text-ink transition-colors">
                        <p class="font-medium text-ink">Tech</p>
                        <p>maria@maidtoglow.com</p>
                    </button>
                </div>
                <p class="text-xs text-sage-400">Password: password</p>
            </div>

            @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
            @endif

            @if(session('status'))
            <div class="mb-6 bg-sage-50 border border-sage-200 rounded-xl p-4 text-sm text-sage-700">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-sage-600 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', 'admin@maidtoglow.com') }}" required autofocus
                           class="w-full bg-white border border-sage-200 rounded-xl px-4 py-3.5 text-ink transition-all focus:outline-none focus:border-sage-400 focus:ring-2 focus:ring-sage-100">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-sage-600 mb-2">Password</label>
                    <input type="password" id="password" name="password" value="password" required
                           class="w-full bg-white border border-sage-200 rounded-xl px-4 py-3.5 text-ink transition-all focus:outline-none focus:border-sage-400 focus:ring-2 focus:ring-sage-100">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" checked
                           class="h-4 w-4 rounded border-sage-300 text-sage-600 focus:ring-sage-500">
                    <label for="remember" class="ml-2 text-sm text-sage-600">Keep me signed in</label>
                </div>

                <button type="submit"
                        class="w-full bg-ink text-cream font-medium rounded-xl px-6 py-4 transition-all hover:bg-sage-800">
                    Sign in
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-sage-500">
                <a href="{{ route('booking.calculator') }}" class="text-ink hover:text-sage-600 transition-colors">← Back to booking</a>
            </p>
        </div>
    </main>

    <footer class="relative border-t border-sage-200/60 py-6">
        <div class="max-w-md mx-auto px-6">
            <p class="text-center text-xs text-sage-400">&copy; {{ date('Y') }} MaidGlow</p>
        </div>
    </footer>

    <script>
        function fillCredentials(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
        }
    </script>
</body>
</html>
