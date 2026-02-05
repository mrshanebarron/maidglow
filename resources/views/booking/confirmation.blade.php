<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed - MaidGlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50">
    <div class="max-w-lg mx-auto py-12 px-4">
        <!-- Success Icon -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Booking Confirmed!</h1>
            <p class="text-gray-600 mt-2">We've received your booking request.</p>
        </div>

        <!-- Booking Details -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="bg-purple-600 text-white p-6">
                <div class="text-sm opacity-80">Confirmation #</div>
                <div class="text-2xl font-bold">{{ str_pad($job->id, 5, '0', STR_PAD_LEFT) }}</div>
            </div>

            <div class="p-6 space-y-4">
                <div class="flex justify-between pb-4 border-b">
                    <div>
                        <div class="text-sm text-gray-500">Service</div>
                        <div class="font-semibold">{{ $job->service->name }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Price</div>
                        <div class="font-semibold text-purple-600">${{ number_format($job->quoted_price, 2) }}</div>
                    </div>
                </div>

                <div class="flex justify-between pb-4 border-b">
                    <div>
                        <div class="text-sm text-gray-500">Date</div>
                        <div class="font-semibold">{{ $job->scheduled_date->format('l, F j, Y') }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Time</div>
                        <div class="font-semibold">{{ \Carbon\Carbon::parse($job->scheduled_time)->format('g:i A') }}</div>
                    </div>
                </div>

                <div>
                    <div class="text-sm text-gray-500">Address</div>
                    <div class="font-semibold">{{ $job->customer->fullAddress() }}</div>
                </div>
            </div>
        </div>

        <!-- What's Next -->
        <div class="bg-blue-50 rounded-xl p-6 mt-6">
            <h3 class="font-semibold text-blue-900 mb-2">What's Next?</h3>
            <ul class="text-sm text-blue-800 space-y-2">
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>You'll receive a confirmation email shortly</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>We'll remind you 24 hours before your appointment</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Our team will arrive at your scheduled time</span>
                </li>
            </ul>
        </div>

        <!-- CTA -->
        <div class="text-center mt-8">
            <a href="{{ route('booking.calculator') }}"
               class="inline-block px-8 py-3 bg-purple-600 text-white font-semibold rounded-xl hover:bg-purple-700 transition-colors">
                Book Another Cleaning
            </a>
        </div>
    </div>
</body>
</html>
