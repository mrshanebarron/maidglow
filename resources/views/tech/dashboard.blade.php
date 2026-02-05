<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Schedule
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-lg mx-auto px-4">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Weekly Earnings Card -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl p-6 text-white mb-6">
                <div class="text-sm opacity-80">This Week's Earnings</div>
                <div class="text-3xl font-bold">${{ number_format($weeklyEarnings, 2) }}</div>
                <a href="{{ route('tech.earnings') }}" class="text-sm text-white/80 hover:text-white mt-2 inline-block">
                    View Details →
                </a>
            </div>

            <!-- Today's Jobs -->
            <h3 class="text-lg font-semibold mb-3">Today's Jobs</h3>

            @forelse($todaysJobs as $job)
                <div class="bg-white rounded-xl shadow-sm mb-4 overflow-hidden">
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <div class="font-semibold text-lg">{{ $job->customer->name }}</div>
                                <div class="text-sm text-gray-500">{{ $job->service->name }}</div>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $job->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $job->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $job->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                            </span>
                        </div>

                        <div class="space-y-2 text-sm">
                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($job->scheduled_time)->format('g:i A') }}
                            </div>
                            <div class="flex items-start text-gray-600">
                                <svg class="w-4 h-4 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>{{ $job->customer->fullAddress() }}</span>
                            </div>
                            @if($job->customer->access_instructions)
                                <div class="flex items-start text-amber-600 bg-amber-50 p-2 rounded">
                                    <svg class="w-4 h-4 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                    <span>{{ $job->customer->access_instructions }}</span>
                                </div>
                            @endif
                            @if($job->customer->has_pets)
                                <div class="flex items-center text-[#4a5b4b]">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.672 1.911a1 1 0 10-1.932.518l.259.966a1 1 0 001.932-.518l-.26-.966zM2.429 4.74a1 1 0 10-.517 1.932l.966.259a1 1 0 00.517-1.932l-.966-.26zm8.814-.569a1 1 0 00-1.415-1.414l-.707.707a1 1 0 101.414 1.414l.708-.707zm-7.072 7.072l.707-.707A1 1 0 003.465 9.12l-.707.707a1 1 0 001.414 1.415zm3.2-5.171a1 1 0 00-1.3 1.3l4 10a1 1 0 001.823.075l1.38-2.759 3.018 3.02a1 1 0 001.414-1.415l-3.019-3.02 2.76-1.379a1 1 0 00-.076-1.822l-10-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $job->customer->pet_details ?? 'Has pets' }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="border-t px-4 py-3 bg-gray-50">
                        <div class="flex gap-2">
                            @if($job->canClockIn())
                                <form action="{{ route('tech.job.clockIn', $job) }}" method="POST" class="flex-1" id="clockInForm-{{ $job->id }}">
                                    @csrf
                                    <input type="hidden" name="latitude" id="lat-{{ $job->id }}">
                                    <input type="hidden" name="longitude" id="lng-{{ $job->id }}">
                                    <button type="submit" onclick="getLocation({{ $job->id }}, 'in')"
                                            class="w-full py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                                        Clock In
                                    </button>
                                </form>
                            @elseif($job->canClockOut())
                                <form action="{{ route('tech.job.clockOut', $job) }}" method="POST" class="flex-1" id="clockOutForm-{{ $job->id }}">
                                    @csrf
                                    <input type="hidden" name="latitude" id="latOut-{{ $job->id }}">
                                    <input type="hidden" name="longitude" id="lngOut-{{ $job->id }}">
                                    <button type="submit" onclick="getLocation({{ $job->id }}, 'out')"
                                            class="w-full py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                        Clock Out
                                    </button>
                                </form>
                            @elseif($job->status === 'completed')
                                <div class="flex-1 py-3 text-center text-green-600 font-semibold">
                                    ✓ Completed
                                </div>
                            @endif

                            <a href="https://maps.google.com/?q={{ urlencode($job->customer->fullAddress()) }}"
                               target="_blank"
                               class="px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                            </a>

                            <a href="tel:{{ $job->customer->phone }}"
                               class="px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl p-8 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-gray-500">No jobs scheduled for today.</p>
                    <p class="text-sm text-gray-400 mt-1">Enjoy your day off!</p>
                </div>
            @endforelse

            <!-- Upcoming Jobs -->
            @if($upcomingJobs->count() > 0)
                <h3 class="text-lg font-semibold mb-3 mt-8">Coming Up</h3>
                @foreach($upcomingJobs as $job)
                    <div class="bg-white rounded-xl shadow-sm p-4 mb-3">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="font-medium">{{ $job->customer->name }}</div>
                                <div class="text-sm text-gray-500">{{ $job->service->name }}</div>
                            </div>
                            <div class="text-right">
                                <div class="font-medium">{{ $job->scheduled_date->format('M j') }}</div>
                                <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($job->scheduled_time)->format('g:i A') }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <script>
        function getLocation(jobId, type) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    if (type === 'in') {
                        document.getElementById('lat-' + jobId).value = position.coords.latitude;
                        document.getElementById('lng-' + jobId).value = position.coords.longitude;
                    } else {
                        document.getElementById('latOut-' + jobId).value = position.coords.latitude;
                        document.getElementById('lngOut-' + jobId).value = position.coords.longitude;
                    }
                }, function(error) {
                    console.log('Geolocation error:', error);
                }, {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                });
            }
        }
    </script>
</x-app-layout>
