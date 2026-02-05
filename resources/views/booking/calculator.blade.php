<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Book Your Cleaning | MaidGlow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
                        },
                        gold: {
                            400: '#c9a962',
                            500: '#b8954f',
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
        [x-cloak] { display: none !important; }
        .text-balance { text-wrap: balance; }

        /* Refined form inputs */
        input:focus, select:focus, textarea:focus {
            outline: none;
        }

        /* Custom radio/checkbox aesthetic */
        .service-card {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .service-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px -8px rgba(0,0,0,0.1);
        }
        .service-card.selected {
            background: linear-gradient(to bottom, #f6f7f6, #e3e7e3);
            border-color: #5f7360;
        }

        /* Subtle texture */
        .texture-overlay {
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            opacity: 0.03;
            pointer-events: none;
        }

        /* Hero image treatment */
        .hero-image {
            mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
            -webkit-mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
        }

        /* Sparkle animation */
        @keyframes sparkle {
            0%, 100% { opacity: 0; transform: scale(0.8); }
            50% { opacity: 1; transform: scale(1); }
        }
        .sparkle {
            animation: sparkle 2s ease-in-out infinite;
        }
        .sparkle:nth-child(2) { animation-delay: 0.5s; }
        .sparkle:nth-child(3) { animation-delay: 1s; }

        /* Price update animation */
        .price-pop {
            animation: pricePop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        @keyframes pricePop {
            0% { transform: scale(1); }
            50% { transform: scale(1.08); }
            100% { transform: scale(1); }
        }

        /* Extra card icons */
        .extra-icon {
            transition: transform 0.2s ease;
        }
        .extra-card:hover .extra-icon {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="bg-cream text-ink antialiased">
    <div x-data="bookingApp()" x-cloak class="min-h-screen flex flex-col">

        <!-- Texture overlay -->
        <div class="fixed inset-0 texture-overlay"></div>

        <!-- Demo Banner -->
        <div class="relative bg-[#333d34] text-white z-50">
            <div class="max-w-6xl mx-auto px-6 lg:px-8 py-3">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-sm font-medium">Demo Mode — Try each portal:</p>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 transition-colors rounded-lg px-3 py-1.5 text-xs font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Admin
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 transition-colors rounded-lg px-3 py-1.5 text-xs font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.384 3.073A1 1 0 015 17.382V6.618a1 1 0 011.036-.861l5.384 3.073M16.5 12h.008"/><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/></svg>
                            Tech
                        </a>
                        <a href="{{ route('customer.login') }}" class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 transition-colors rounded-lg px-3 py-1.5 text-xs font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            Customer
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header -->
        <header class="relative border-b border-sage-200/60 bg-cream/80 backdrop-blur-sm sticky top-0 z-50">
            <div class="max-w-6xl mx-auto px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <a href="/" class="font-serif text-2xl tracking-tight flex items-center gap-2">
                        <svg class="w-8 h-8 text-sage-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 3l1.5 3.5L17 8l-3.5 1.5L12 13l-1.5-3.5L7 8l3.5-1.5L12 3z"/>
                            <path d="M5 16l1 2.5L8.5 20l-2.5 1L5 24l-1-2.5L1.5 20l2.5-1L5 16z" opacity="0.5"/>
                            <path d="M19 14l0.75 1.5 1.75.75-1.75.75L19 18.5l-0.75-1.5-1.75-.75 1.75-.75L19 14z" opacity="0.7"/>
                        </svg>
                        MaidGlow
                    </a>
                    <nav class="flex items-center gap-6">
                        <a href="tel:+14045551234" class="hidden sm:flex items-center gap-2 text-sm text-sage-600 hover:text-ink transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            (404) 555-1234
                        </a>
                        <a href="{{ route('customer.login') }}" class="text-sm font-medium text-sage-700 hover:text-ink transition-colors">Sign in</a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Hero -->
        <section class="relative overflow-hidden bg-gradient-to-b from-sage-100/50 to-cream">
            <div class="max-w-6xl mx-auto px-6 lg:px-8 py-16 lg:py-24">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Left: Copy -->
                    <div class="relative z-10">
                        <div class="inline-flex items-center gap-2 bg-white/80 backdrop-blur border border-sage-200 rounded-full px-4 py-2 mb-6">
                            <span class="flex h-2 w-2 rounded-full bg-green-500"></span>
                            <span class="text-sm text-sage-600">Serving Metro Atlanta</span>
                        </div>
                        <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl tracking-tight text-balance mb-6">
                            A cleaner home,<br>
                            <span class="italic text-sage-600">without the hassle</span>
                        </h1>
                        <p class="text-lg text-sage-600 max-w-lg mb-8">
                            Professional cleaning you can count on. Get an instant quote in seconds — no commitments, no card required.
                        </p>
                        <div class="flex flex-wrap items-center gap-6 text-sm">
                            <div class="flex items-center gap-2">
                                <div class="flex -space-x-2">
                                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop&crop=face" class="w-8 h-8 rounded-full border-2 border-white object-cover" alt="">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face" class="w-8 h-8 rounded-full border-2 border-white object-cover" alt="">
                                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop&crop=face" class="w-8 h-8 rounded-full border-2 border-white object-cover" alt="">
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-gold-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span class="font-semibold text-ink">4.9</span>
                                    <span class="text-sage-500">(500+ reviews)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Right: Image -->
                    <div class="relative">
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-sage-900/20">
                            <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=800&h=600&fit=crop"
                                 alt="Spotless modern kitchen"
                                 class="w-full h-[400px] lg:h-[500px] object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-ink/20 to-transparent"></div>
                            <!-- Floating badge -->
                            <div class="absolute bottom-6 left-6 right-6 bg-white/95 backdrop-blur rounded-2xl p-4 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-sage-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-sage-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-ink">100% Satisfaction Guaranteed</p>
                                    <p class="text-sm text-sage-500">We'll re-clean for free if you're not happy</p>
                                </div>
                            </div>
                        </div>
                        <!-- Decorative sparkles -->
                        <div class="absolute -top-4 -right-4 w-8 h-8 text-gold-400 sparkle">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0l2.5 9.5L24 12l-9.5 2.5L12 24l-2.5-9.5L0 12l9.5-2.5L12 0z"/></svg>
                        </div>
                        <div class="absolute top-1/3 -left-2 w-5 h-5 text-gold-400 sparkle">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0l2.5 9.5L24 12l-9.5 2.5L12 24l-2.5-9.5L0 12l9.5-2.5L12 0z"/></svg>
                        </div>
                        <div class="absolute bottom-1/4 -right-3 w-6 h-6 text-gold-400 sparkle">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0l2.5 9.5L24 12l-9.5 2.5L12 24l-2.5-9.5L0 12l9.5-2.5L12 0z"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main -->
        <main class="relative flex-1 py-16 lg:py-24">
            <div class="max-w-6xl mx-auto px-6 lg:px-8">

                <div class="grid lg:grid-cols-5 gap-12 lg:gap-16">

                    <!-- Left: Form -->
                    <div class="lg:col-span-3 space-y-12">

                        <!-- Services -->
                        <section>
                            <h2 class="font-serif text-2xl mb-6">What do you need?</h2>
                            <div class="grid gap-4">
                                @php
                                    $serviceIcons = [
                                        'Standard Clean' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>',
                                        'Deep Clean' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/>',
                                        'Move In/Out Clean' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>',
                                    ];
                                @endphp
                                @foreach($services as $service)
                                <label class="service-card group flex items-start gap-4 bg-white border border-sage-200 rounded-2xl p-5 cursor-pointer"
                                       :class="{ 'selected': selectedService == {{ $service->id }} }">
                                    <input type="radio" name="service" class="sr-only"
                                           @click="selectService({{ $service->id }}, {{ $service->base_price }}, {{ $service->price_per_bedroom }}, {{ $service->price_per_bathroom }}, {{ $service->price_per_sqft }}, '{{ $service->name }}')">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors"
                                         :class="selectedService == {{ $service->id }} ? 'bg-sage-600 text-white' : 'bg-sage-100 text-sage-600 group-hover:bg-sage-200'">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            {!! $serviceIcons[$service->name] ?? '<path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>' !!}
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="font-semibold text-ink">{{ $service->name }}</span>
                                            <span class="text-sage-500 text-sm">from ${{ number_format($service->base_price, 0) }}</span>
                                        </div>
                                        <p class="text-sage-500 text-sm leading-relaxed">{{ $service->description }}</p>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-colors mt-1"
                                         :class="selectedService == {{ $service->id }} ? 'border-sage-600 bg-sage-600' : 'border-sage-300'">
                                        <svg x-show="selectedService == {{ $service->id }}" class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </section>

                        <!-- Home Details -->
                        <section>
                            <h2 class="font-serif text-2xl mb-6">Tell us about your home</h2>
                            <div class="bg-white border border-sage-200 rounded-2xl p-6">
                                <div class="grid sm:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-sm text-sage-600 mb-2">Bedrooms</label>
                                        <select x-model="bedrooms" @change="calculate()"
                                                class="w-full bg-sage-50 border-0 rounded-xl px-4 py-3.5 text-ink font-medium focus:ring-2 focus:ring-sage-300">
                                            @for($i = 1; $i <= 7; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-sage-600 mb-2">Bathrooms</label>
                                        <select x-model="bathrooms" @change="calculate()"
                                                class="w-full bg-sage-50 border-0 rounded-xl px-4 py-3.5 text-ink font-medium focus:ring-2 focus:ring-sage-300">
                                            @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-sage-600 mb-2">Sq ft <span class="text-sage-400">(optional)</span></label>
                                        <input type="number" x-model="sqft" @input="calculate()" placeholder="—"
                                               class="w-full bg-sage-50 border-0 rounded-xl px-4 py-3.5 text-ink font-medium placeholder:text-sage-400 focus:ring-2 focus:ring-sage-300">
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Frequency -->
                        <section>
                            <h2 class="font-serif text-2xl mb-6">How often?</h2>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <template x-for="f in frequencies" :key="f.value">
                                    <button type="button"
                                            @click="frequency = f.value; calculate()"
                                            class="relative bg-white border rounded-xl px-4 py-4 text-center transition-all"
                                            :class="frequency === f.value ? 'border-sage-400 bg-sage-50 shadow-sm' : 'border-sage-200 hover:border-sage-300'">
                                        <span class="block font-medium" x-text="f.label"></span>
                                        <span class="block text-xs text-sage-500 mt-1" x-show="f.save" x-text="'Save ' + f.save"></span>
                                    </button>
                                </template>
                            </div>
                        </section>

                        <!-- Extras -->
                        <section>
                            <h2 class="font-serif text-2xl mb-2">Add extras</h2>
                            <p class="text-sage-500 text-sm mb-6">Optional add-ons for a deeper clean</p>
                            <div class="grid sm:grid-cols-2 gap-3">
                                <template x-for="extra in extras" :key="extra.id">
                                    <button type="button"
                                            @click="toggleExtra(extra.id)"
                                            class="extra-card flex items-center justify-between bg-white border rounded-xl px-5 py-4 text-left transition-all"
                                            :class="selectedExtras.includes(extra.id) ? 'border-sage-400 bg-sage-50 shadow-sm' : 'border-sage-200 hover:border-sage-300 hover:shadow-sm'">
                                        <div class="flex items-center gap-3">
                                            <div class="extra-icon w-10 h-10 rounded-lg flex items-center justify-center transition-colors"
                                                 :class="selectedExtras.includes(extra.id) ? 'bg-sage-600 text-white' : 'bg-sage-100 text-sage-500'">
                                                <svg x-show="extra.id === 'fridge'" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>
                                                    <rect x="4" y="3" width="16" height="18" rx="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <line x1="4" y1="10" x2="20" y2="10"/>
                                                </svg>
                                                <svg x-show="extra.id === 'oven'" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <rect x="3" y="4" width="18" height="16" rx="2"/>
                                                    <rect x="6" y="10" width="12" height="7" rx="1"/>
                                                    <circle cx="7" cy="7" r="1"/><circle cx="11" cy="7" r="1"/><circle cx="15" cy="7" r="1"/>
                                                </svg>
                                                <svg x-show="extra.id === 'windows'" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <rect x="3" y="3" width="18" height="18" rx="2"/><line x1="12" y1="3" x2="12" y2="21"/><line x1="3" y1="12" x2="21" y2="12"/>
                                                </svg>
                                                <svg x-show="extra.id === 'laundry'" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <rect x="3" y="2" width="18" height="20" rx="2"/><circle cx="12" cy="13" r="5"/><circle cx="12" cy="13" r="2"/>
                                                    <circle cx="7" cy="6" r="1"/><circle cx="10" cy="6" r="1"/>
                                                </svg>
                                                <svg x-show="extra.id === 'cabinets'" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <rect x="3" y="4" width="18" height="16" rx="1"/><line x1="12" y1="4" x2="12" y2="20"/><line x1="3" y1="12" x2="21" y2="12"/>
                                                    <line x1="8" y1="8" x2="10" y2="8"/><line x1="14" y1="8" x2="16" y2="8"/>
                                                    <line x1="8" y1="16" x2="10" y2="16"/><line x1="14" y1="16" x2="16" y2="16"/>
                                                </svg>
                                                <svg x-show="extra.id === 'garage'" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path d="M3 21V8l9-5 9 5v13"/><rect x="5" y="10" width="14" height="11"/>
                                                    <line x1="5" y1="14" x2="19" y2="14"/><line x1="5" y1="17" x2="19" y2="17"/>
                                                </svg>
                                            </div>
                                            <span class="font-medium" x-text="extra.name"></span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="text-sage-500 text-sm" x-text="'+$' + extra.price"></span>
                                            <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition-colors"
                                                 :class="selectedExtras.includes(extra.id) ? 'border-sage-600 bg-sage-600' : 'border-sage-300'">
                                                <svg x-show="selectedExtras.includes(extra.id)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </section>
                    </div>

                    <!-- Right: Summary -->
                    <div class="lg:col-span-2">
                        <div class="lg:sticky lg:top-28">
                            <div class="bg-white border border-sage-200 rounded-3xl overflow-hidden shadow-lg shadow-sage-900/5">
                                <!-- Summary Header -->
                                <div class="px-8 pt-8 pb-6 border-b border-sage-100 bg-gradient-to-b from-sage-50/50 to-white">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-sage-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-sage-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                            </svg>
                                        </div>
                                        <h2 class="font-serif text-xl">Your estimate</h2>
                                    </div>
                                </div>

                                <!-- Summary Body -->
                                <div class="px-8 py-6 space-y-4">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-sage-500">Service</span>
                                        <span class="font-medium" x-text="serviceName || '—'"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-sage-500">Home</span>
                                        <span class="font-medium" x-text="bedrooms + ' bed · ' + bathrooms + ' bath'"></span>
                                    </div>
                                    <div class="flex justify-between text-sm" x-show="sqft" x-transition>
                                        <span class="text-sage-500">Size</span>
                                        <span class="font-medium" x-text="Number(sqft).toLocaleString() + ' sq ft'"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-sage-500">Frequency</span>
                                        <span class="font-medium" x-text="frequencies.find(f => f.value === frequency)?.label"></span>
                                    </div>
                                    <template x-if="selectedExtras.length">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-sage-500">Extras</span>
                                            <span class="font-medium text-right text-xs max-w-[150px]" x-text="selectedExtras.map(id => extras.find(e => e.id === id)?.name).join(', ')"></span>
                                        </div>
                                    </template>
                                </div>

                                <!-- Price -->
                                <div class="px-8 py-6 bg-gradient-to-b from-sage-50 to-sage-100/50 border-t border-sage-100">
                                    <div class="flex items-baseline justify-between">
                                        <span class="text-sage-600 font-medium">Total</span>
                                        <div class="text-right">
                                            <span class="font-serif text-4xl price-display"
                                                  x-text="'$' + price"
                                                  x-ref="priceEl"></span>
                                            <span class="text-sage-500 text-sm ml-1" x-show="frequency !== 'once'">/ visit</span>
                                        </div>
                                    </div>
                                    <div x-show="discount" x-transition class="mt-3 inline-flex items-center gap-2 bg-green-100 text-green-700 text-xs font-medium px-3 py-1.5 rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span x-text="discount + '% saved'"></span> with recurring service
                                    </div>
                                </div>

                                <!-- CTA -->
                                <div class="p-6">
                                    <button @click="proceed()"
                                            :disabled="!selectedService"
                                            class="group w-full bg-ink text-cream font-medium rounded-xl px-6 py-4 transition-all hover:bg-sage-800 hover:shadow-lg hover:shadow-sage-900/20 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:shadow-none flex items-center justify-center gap-2">
                                        <span>Continue</span>
                                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                        </svg>
                                    </button>
                                    <p class="text-center text-sage-400 text-xs mt-4">No payment required to book</p>
                                </div>
                            </div>

                            <!-- Trust -->
                            <div class="mt-8 grid grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="w-10 h-10 rounded-full bg-sage-100 flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-5 h-5 text-sage-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-sage-500 font-medium">Insured</p>
                                </div>
                                <div class="text-center">
                                    <div class="w-10 h-10 rounded-full bg-sage-100 flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-5 h-5 text-sage-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-sage-500 font-medium">5-Star Rated</p>
                                </div>
                                <div class="text-center">
                                    <div class="w-10 h-10 rounded-full bg-sage-100 flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-5 h-5 text-sage-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-sage-500 font-medium">Vetted Pros</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="relative border-t border-sage-200/60 py-8">
            <div class="max-w-6xl mx-auto px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-sage-400">
                    <span>&copy; {{ date('Y') }} MaidGlow</span>
                    <div class="flex gap-6">
                        <a href="{{ route('login') }}" class="hover:text-sage-600 transition-colors">Staff</a>
                        <a href="{{ route('customer.login') }}" class="hover:text-sage-600 transition-colors">Customer portal</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script>
        function bookingApp() {
            return {
                selectedService: null,
                serviceName: '',
                serviceData: { base: 0, bedroom: 0, bathroom: 0, sqft: 0 },
                bedrooms: 3,
                bathrooms: 2,
                sqft: '',
                frequency: 'once',
                selectedExtras: [],
                price: 0,
                discount: 0,

                frequencies: [
                    { value: 'once', label: 'One-time', save: null },
                    { value: 'weekly', label: 'Weekly', save: '20%' },
                    { value: 'biweekly', label: 'Bi-weekly', save: '15%' },
                    { value: 'monthly', label: 'Monthly', save: '10%' },
                ],

                extras: [
                    { id: 'fridge', name: 'Inside fridge', price: 40 },
                    { id: 'oven', name: 'Inside oven', price: 35 },
                    { id: 'windows', name: 'Interior windows', price: 45 },
                    { id: 'laundry', name: 'Laundry', price: 30 },
                    { id: 'cabinets', name: 'Inside cabinets', price: 50 },
                    { id: 'garage', name: 'Garage sweep', price: 60 },
                ],

                selectService(id, base, bedroom, bathroom, sqft, name) {
                    this.selectedService = id;
                    this.serviceName = name;
                    this.serviceData = { base, bedroom, bathroom, sqft };
                    this.calculate();
                },

                toggleExtra(id) {
                    const idx = this.selectedExtras.indexOf(id);
                    if (idx === -1) {
                        this.selectedExtras.push(id);
                    } else {
                        this.selectedExtras.splice(idx, 1);
                    }
                    this.calculate();
                },

                calculate() {
                    if (!this.selectedService) {
                        this.price = 0;
                        return;
                    }

                    let total = this.serviceData.base;
                    total += this.bedrooms * this.serviceData.bedroom;
                    total += this.bathrooms * this.serviceData.bathroom;
                    total += (parseInt(this.sqft) || 0) * this.serviceData.sqft;

                    this.selectedExtras.forEach(id => {
                        const extra = this.extras.find(e => e.id === id);
                        if (extra) total += extra.price;
                    });

                    this.discount = 0;
                    if (this.frequency === 'weekly') this.discount = 20;
                    else if (this.frequency === 'biweekly') this.discount = 15;
                    else if (this.frequency === 'monthly') this.discount = 10;

                    if (this.discount) {
                        total *= (1 - this.discount / 100);
                    }

                    const newPrice = Math.round(total);
                    if (newPrice !== this.price) {
                        this.price = newPrice;
                        // Trigger price pop animation
                        this.$nextTick(() => {
                            const el = this.$refs.priceEl;
                            if (el) {
                                el.classList.remove('price-pop');
                                void el.offsetWidth; // Force reflow
                                el.classList.add('price-pop');
                            }
                        });
                    }
                },

                proceed() {
                    if (!this.selectedService) return;
                    const params = new URLSearchParams({
                        service_id: this.selectedService,
                        bedrooms: this.bedrooms,
                        bathrooms: this.bathrooms,
                        square_feet: this.sqft || 0,
                        frequency: this.frequency,
                        extras: this.selectedExtras.join(','),
                        price: this.price
                    });
                    window.location.href = '{{ route("booking.form") }}?' + params.toString();
                }
            }
        }

        // Entrance animations
        document.addEventListener('DOMContentLoaded', () => {
            // Hero section
            gsap.from('section:first-of-type > div > div:first-child > *', {
                y: 30, opacity: 0, duration: 0.7, stagger: 0.1, ease: 'power2.out'
            });
            gsap.from('section:first-of-type > div > div:last-child', {
                x: 40, opacity: 0, duration: 0.8, delay: 0.3, ease: 'power2.out'
            });
            // Form sections
            gsap.from('main section', {
                y: 30, opacity: 0, duration: 0.6, stagger: 0.15, delay: 0.5, ease: 'power2.out'
            });
            // Summary card
            gsap.from('.lg\\:col-span-2 > div', {
                y: 40, opacity: 0, duration: 0.7, delay: 0.8, ease: 'power2.out'
            });
        });
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
