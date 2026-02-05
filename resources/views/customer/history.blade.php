<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service History - MaidGlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back
            </a>
            <h1 class="text-xl font-bold text-[#4a5b4b]">MaidGlow</h1>
            <div class="w-16"></div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Service History</h2>

        @forelse($jobs as $job)
            <div class="bg-white rounded-xl shadow-sm p-5 mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="font-semibold text-lg">{{ $job->service->name }}</div>
                        <div class="text-gray-500">{{ $job->scheduled_date->format('l, F j, Y') }}</div>
                        <div class="text-gray-500">{{ \Carbon\Carbon::parse($job->scheduled_time)->format('g:i A') }}</div>
                        @if($job->assignedTech)
                            <div class="mt-2 text-sm text-gray-600">
                                Technician: {{ $job->assignedTech->name }}
                            </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $job->status_color }}-100 text-{{ $job->status_color }}-800">
                            {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                        </span>
                        <div class="text-xl font-bold mt-2">${{ number_format($job->quoted_price, 2) }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl p-8 text-center">
                <p class="text-gray-500">No service history yet.</p>
            </div>
        @endforelse

        {{ $jobs->links() }}
    </main>
</body>
</html>
