<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - MaidGlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-[#4a5b4b]">MaidGlow</h1>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600">{{ $customer->name }}</span>
                <form action="{{ route('customer.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Welcome back, {{ explode(' ', $customer->name)[0] }}!</h2>
            <p class="text-gray-500">Manage your cleaning appointments</p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 gap-4 mb-8">
            <a href="{{ route('booking.calculator') }}" class="bg-[#4a5b4b] hover:bg-[#3d4a3e] text-white rounded-xl p-6 text-center transition-colors">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span class="font-semibold">Book New Cleaning</span>
            </a>
            <a href="{{ route('customer.history') }}" class="bg-white hover:bg-gray-50 text-gray-800 rounded-xl p-6 text-center shadow-sm border transition-colors">
                <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-semibold">View History</span>
            </a>
        </div>

        <!-- Upcoming Appointments -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-4">Upcoming Appointments</h3>
            @forelse($upcomingJobs as $job)
                <div class="bg-white rounded-xl shadow-sm p-5 mb-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-semibold text-lg">{{ $job->service->name }}</div>
                            <div class="text-gray-500 mt-1">
                                {{ $job->scheduled_date->format('l, F j, Y') }}
                            </div>
                            <div class="text-gray-500">
                                {{ \Carbon\Carbon::parse($job->scheduled_time)->format('g:i A') }}
                            </div>
                            @if($job->assignedTech)
                                <div class="mt-2 text-sm text-gray-600">
                                    Technician: {{ $job->assignedTech->name }}
                                </div>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $job->status_color }}-100 text-{{ $job->status_color }}-800">
                                @if($job->status === 'in_progress')
                                    <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></span>
                                @endif
                                {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                            </span>
                            <div class="text-xl font-bold mt-2">${{ number_format($job->quoted_price, 2) }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-gray-500 mb-4">No upcoming appointments</p>
                    <a href="{{ route('booking.calculator') }}" class="inline-block px-6 py-2 bg-[#4a5b4b] text-white rounded-lg hover:bg-[#3d4a3e]">
                        Schedule Now
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Recent History -->
        @if($pastJobs->count() > 0)
        <div>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Recent Cleanings</h3>
                <a href="{{ route('customer.history') }}" class="text-[#4a5b4b] hover:underline text-sm">View All</a>
            </div>
            @foreach($pastJobs as $job)
                <div class="bg-white rounded-xl shadow-sm p-4 mb-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="font-medium">{{ $job->service->name }}</div>
                            <div class="text-sm text-gray-500">{{ $job->scheduled_date->format('M j, Y') }}</div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center text-green-600 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Completed
                            </span>
                            <div class="font-semibold mt-1">${{ number_format($job->quoted_price, 2) }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

        <!-- Account Info -->
        <div class="mt-8 bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">Your Property</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div>
                    <div class="text-2xl font-bold text-[#4a5b4b]">{{ $customer->bedrooms }}</div>
                    <div class="text-sm text-gray-500">Bedrooms</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-[#4a5b4b]">{{ $customer->bathrooms }}</div>
                    <div class="text-sm text-gray-500">Bathrooms</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-[#4a5b4b]">{{ number_format($customer->square_feet) }}</div>
                    <div class="text-sm text-gray-500">Sq Ft</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-[#4a5b4b]">{{ $customer->cleaningJobs->where('status', 'completed')->count() }}</div>
                    <div class="text-sm text-gray-500">Cleanings</div>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t text-sm text-gray-600">
                <p>{{ $customer->address }}, {{ $customer->city }}, {{ $customer->state }} {{ $customer->zip }}</p>
            </div>
        </div>
    </main>
</body>
</html>
