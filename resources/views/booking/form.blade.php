<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Complete Your Booking | MaidGlow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/gsap@3/dist/gsap.min.js"></script>
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
                            700: '#3d4a3e',
                            800: '#333d34',
                            900: '#2c332d',
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
        /* Subtle texture */
        .texture-overlay {
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            opacity: 0.03;
            pointer-events: none;
        }

        /* Form inputs */
        .form-input {
            @apply w-full bg-white border border-sage-200 rounded-xl px-4 py-3.5 text-ink transition-all;
            @apply focus:outline-none focus:border-sage-400 focus:ring-2 focus:ring-sage-100;
        }
        .form-input::placeholder {
            @apply text-sage-400;
        }
    </style>
</head>
<body class="bg-cream text-ink antialiased">
    <!-- Texture overlay -->
    <div class="fixed inset-0 texture-overlay"></div>

    <!-- Header -->
    <header class="relative border-b border-sage-200/60">
        <div class="max-w-3xl mx-auto px-6">
            <div class="flex items-center justify-between h-20">
                <a href="{{ route('booking.calculator') }}" class="font-serif text-2xl tracking-tight">MaidGlow</a>
                <a href="tel:+14045551234" class="text-sm text-sage-600 hover:text-ink transition-colors">(404) 555-1234</a>
            </div>
        </div>
    </header>

    <!-- Progress -->
    <div class="relative border-b border-sage-200/60 bg-white/50">
        <div class="max-w-3xl mx-auto px-6 py-5">
            <div class="flex items-center justify-center gap-3 text-sm">
                <span class="flex items-center gap-2 text-sage-400">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-sage-600 text-white text-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    <span class="hidden sm:inline">Service</span>
                </span>
                <span class="w-8 h-px bg-sage-300"></span>
                <span class="flex items-center gap-2 text-ink font-medium">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-ink text-cream text-xs">2</span>
                    <span class="hidden sm:inline">Details</span>
                </span>
                <span class="w-8 h-px bg-sage-200"></span>
                <span class="flex items-center gap-2 text-sage-300">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full border-2 border-sage-200 text-xs">3</span>
                    <span class="hidden sm:inline">Confirm</span>
                </span>
            </div>
        </div>
    </div>

    <main class="relative max-w-3xl mx-auto px-6 py-12">
        <!-- Summary Card -->
        @if($preselected['price'])
        <div class="mb-10 bg-white border border-sage-200 rounded-2xl p-6 flex items-center justify-between">
            <div>
                <p class="font-medium">{{ $services->find($preselected['service_id'])?->name ?? 'Service' }}</p>
                <p class="text-sm text-sage-500 mt-1">{{ $preselected['bedrooms'] }} bed · {{ $preselected['bathrooms'] }} bath</p>
            </div>
            <div class="text-right">
                <p class="font-serif text-2xl">${{ number_format($preselected['price'], 0) }}</p>
                <a href="{{ route('booking.calculator') }}" class="text-xs text-sage-500 hover:text-ink transition-colors">Edit</a>
            </div>
        </div>
        @endif

        <form action="{{ route('booking.store') }}" method="POST" class="space-y-10">
            @csrf
            <input type="hidden" name="service_id" value="{{ $preselected['service_id'] ?? '' }}">
            <input type="hidden" name="bedrooms" value="{{ $preselected['bedrooms'] ?? 3 }}">
            <input type="hidden" name="bathrooms" value="{{ $preselected['bathrooms'] ?? 2 }}">
            <input type="hidden" name="square_feet" value="{{ $preselected['square_feet'] ?? '' }}">

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-700">
                @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <!-- Contact -->
            <section>
                <h2 class="font-serif text-2xl mb-6">Contact information</h2>
                <div class="bg-white border border-sage-200 rounded-2xl p-6 space-y-5">
                    <div>
                        <label for="name" class="block text-sm text-sage-600 mb-2">Full name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name"
                               class="form-input">
                    </div>
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label for="email" class="block text-sm text-sage-600 mb-2">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                                   class="form-input">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm text-sage-600 mb-2">Phone</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required autocomplete="tel"
                                   class="form-input">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Address -->
            <section>
                <h2 class="font-serif text-2xl mb-6">Service address</h2>
                <div class="bg-white border border-sage-200 rounded-2xl p-6 space-y-5">
                    <div>
                        <label for="address" class="block text-sm text-sage-600 mb-2">Street address</label>
                        <input type="text" id="address" name="address" value="{{ old('address') }}" required autocomplete="street-address"
                               class="form-input">
                    </div>
                    <div class="grid grid-cols-5 gap-4">
                        <div class="col-span-2">
                            <label for="city" class="block text-sm text-sage-600 mb-2">City</label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}" required autocomplete="address-level2"
                                   class="form-input">
                        </div>
                        <div class="col-span-1">
                            <label for="state" class="block text-sm text-sage-600 mb-2">State</label>
                            <input type="text" id="state" name="state" value="{{ old('state', 'GA') }}" required maxlength="2"
                                   class="form-input uppercase text-center">
                        </div>
                        <div class="col-span-2">
                            <label for="zip" class="block text-sm text-sage-600 mb-2">ZIP</label>
                            <input type="text" id="zip" name="zip" value="{{ old('zip') }}" required autocomplete="postal-code"
                                   class="form-input">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Schedule -->
            <section>
                <h2 class="font-serif text-2xl mb-6">Preferred schedule</h2>
                <div class="bg-white border border-sage-200 rounded-2xl p-6">
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label for="preferred_date" class="block text-sm text-sage-600 mb-2">Date</label>
                            <input type="date" id="preferred_date" name="preferred_date" value="{{ old('preferred_date') }}" required
                                   min="{{ now()->addDay()->format('Y-m-d') }}"
                                   class="form-input">
                        </div>
                        <div>
                            <label for="preferred_time" class="block text-sm text-sage-600 mb-2">Time window</label>
                            <select id="preferred_time" name="preferred_time" required class="form-input">
                                <option value="">Select...</option>
                                <option value="08:00" {{ old('preferred_time') == '08:00' ? 'selected' : '' }}>8 AM – 10 AM</option>
                                <option value="10:00" {{ old('preferred_time') == '10:00' ? 'selected' : '' }}>10 AM – 12 PM</option>
                                <option value="12:00" {{ old('preferred_time') == '12:00' ? 'selected' : '' }}>12 PM – 2 PM</option>
                                <option value="14:00" {{ old('preferred_time') == '14:00' ? 'selected' : '' }}>2 PM – 4 PM</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Additional -->
            <section>
                <h2 class="font-serif text-2xl mb-6">Additional details</h2>
                <div class="bg-white border border-sage-200 rounded-2xl p-6 space-y-5">
                    <div class="flex items-start gap-3">
                        <input type="checkbox" id="has_pets" name="has_pets" value="1" {{ old('has_pets') ? 'checked' : '' }}
                               class="mt-1 h-5 w-5 rounded border-sage-300 text-sage-600 focus:ring-sage-500">
                        <label for="has_pets" class="text-sm">
                            <span class="font-medium">I have pets</span>
                            <span class="block text-sage-500 mt-0.5">We'll take extra care with doors and gates</span>
                        </label>
                    </div>

                    <div id="pet_details_wrapper" class="{{ old('has_pets') ? '' : 'hidden' }}">
                        <label for="pet_details" class="block text-sm text-sage-600 mb-2">Pet details</label>
                        <input type="text" id="pet_details" name="pet_details" value="{{ old('pet_details') }}"
                               placeholder="Type and number of pets" class="form-input">
                    </div>

                    <div>
                        <label for="access_instructions" class="block text-sm text-sage-600 mb-2">Access instructions</label>
                        <textarea id="access_instructions" name="access_instructions" rows="2"
                                  placeholder="Gate code, key location, entry notes..." class="form-input resize-none">{{ old('access_instructions') }}</textarea>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm text-sage-600 mb-2">Special requests <span class="text-sage-400">(optional)</span></label>
                        <textarea id="notes" name="notes" rows="2"
                                  placeholder="Focus areas, preferences..." class="form-input resize-none">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </section>

            <!-- Submit -->
            <div class="pt-2">
                <button type="submit"
                        class="w-full bg-ink text-cream font-medium rounded-xl px-6 py-4 transition-all hover:bg-sage-800">
                    Complete booking
                </button>
                <p class="text-center text-sage-400 text-xs mt-4">We'll confirm your appointment within 2 hours</p>
            </div>
        </form>
    </main>

    <!-- Footer -->
    <footer class="relative border-t border-sage-200/60 py-8 mt-8">
        <div class="max-w-3xl mx-auto px-6">
            <p class="text-center text-sm text-sage-400">&copy; {{ date('Y') }} MaidGlow</p>
        </div>
    </footer>

    <script>
        document.getElementById('has_pets').addEventListener('change', function() {
            document.getElementById('pet_details_wrapper').classList.toggle('hidden', !this.checked);
        });

        // Entrance
        document.addEventListener('DOMContentLoaded', () => {
            gsap.from('main > *', { y: 20, opacity: 0, duration: 0.5, stagger: 0.1, ease: 'power2.out' });
        });
    </script>
</body>
</html>
