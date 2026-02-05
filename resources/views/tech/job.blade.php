<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Job Details
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-lg mx-auto px-4">
            <!-- Status Banner -->
            @if($job->status === 'in_progress')
                <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 mb-4 rounded-r-lg">
                    <div class="flex items-center">
                        <div class="animate-pulse w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <span class="font-medium text-yellow-800">Job In Progress</span>
                    </div>
                </div>
            @elseif($job->status === 'completed')
                <div class="bg-green-100 border-l-4 border-green-500 p-4 mb-4 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium text-green-800">Job Completed</span>
                    </div>
                </div>
            @endif

            <!-- Customer Card -->
            <div class="bg-white rounded-xl shadow-sm p-5 mb-4">
                <h3 class="font-semibold text-lg mb-3">{{ $job->customer->name }}</h3>

                <div class="space-y-3">
                    <div>
                        <div class="text-sm text-gray-500">Address</div>
                        <div class="font-medium">{{ $job->customer->address }}</div>
                        <div class="text-gray-600">{{ $job->customer->city }}, {{ $job->customer->state }} {{ $job->customer->zip }}</div>
                    </div>

                    <div class="flex gap-3">
                        <a href="https://maps.google.com/?q={{ urlencode($job->customer->full_address) }}" target="_blank"
                           class="flex-1 px-4 py-3 bg-blue-500 text-white rounded-lg text-center font-medium">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Directions
                        </a>
                        <a href="tel:{{ $job->customer->phone }}"
                           class="flex-1 px-4 py-3 bg-green-500 text-white rounded-lg text-center font-medium">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            Call
                        </a>
                    </div>
                </div>
            </div>

            <!-- Service Card -->
            <div class="bg-white rounded-xl shadow-sm p-5 mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-sm text-gray-500">Service</div>
                        <div class="font-semibold text-lg">{{ $job->service->name }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Scheduled</div>
                        <div class="font-medium">{{ $job->scheduled_date->format('M j') }}</div>
                        <div class="text-gray-600">{{ \Carbon\Carbon::parse($job->scheduled_time)->format('g:i A') }}</div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t grid grid-cols-3 gap-4 text-center text-sm">
                    <div>
                        <div class="font-bold text-lg">{{ $job->customer->bedrooms }}</div>
                        <div class="text-gray-500">Beds</div>
                    </div>
                    <div>
                        <div class="font-bold text-lg">{{ $job->customer->bathrooms }}</div>
                        <div class="text-gray-500">Baths</div>
                    </div>
                    <div>
                        <div class="font-bold text-lg">{{ number_format($job->customer->square_feet) }}</div>
                        <div class="text-gray-500">Sq Ft</div>
                    </div>
                </div>

                @if($job->customer->has_pets)
                    <div class="mt-3 pt-3 border-t">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4z"/>
                            </svg>
                            Has Pets
                        </span>
                    </div>
                @endif
            </div>

            <!-- Notes Card -->
            @if($job->notes)
            <div class="bg-white rounded-xl shadow-sm p-5 mb-4">
                <h3 class="font-semibold mb-2">Special Instructions</h3>
                <p class="text-gray-700">{{ $job->notes }}</p>
            </div>
            @endif

            <!-- Time Tracking -->
            @if($job->timeEntries->count() > 0)
            <div class="bg-white rounded-xl shadow-sm p-5 mb-4">
                <h3 class="font-semibold mb-3">Time Log</h3>
                <div class="space-y-2">
                    @foreach($job->timeEntries as $entry)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">
                                {{ $entry->type === 'clock_in' ? 'Started' : 'Finished' }}
                            </span>
                            <span class="font-medium">{{ $entry->created_at->format('g:i A') }}</span>
                        </div>
                    @endforeach
                </div>
                @if($job->status === 'completed')
                    <div class="mt-3 pt-3 border-t flex justify-between">
                        <span class="font-medium">Total Time</span>
                        <span class="font-bold text-green-600">{{ $job->formattedTimeWorked() }}</span>
                    </div>
                @endif
            </div>
            @endif

            <!-- Clock In/Out Buttons -->
            @if($job->status === 'scheduled')
                <form action="{{ route('tech.job.clockIn', $job) }}" method="POST" id="clockInForm">
                    @csrf
                    <input type="hidden" name="latitude" id="clockInLat">
                    <input type="hidden" name="longitude" id="clockInLng">
                    <button type="submit"
                            class="w-full py-4 bg-green-500 hover:bg-green-600 text-white rounded-xl font-bold text-lg shadow-lg">
                        Clock In
                    </button>
                </form>
            @elseif($job->status === 'in_progress')
                <form action="{{ route('tech.job.clockOut', $job) }}" method="POST" id="clockOutForm">
                    @csrf
                    <input type="hidden" name="latitude" id="clockOutLat">
                    <input type="hidden" name="longitude" id="clockOutLng">
                    <button type="submit"
                            class="w-full py-4 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold text-lg shadow-lg">
                        Clock Out - Complete Job
                    </button>
                </form>
            @endif

            <!-- Add Notes -->
            @if(in_array($job->status, ['in_progress', 'completed']))
            <div class="mt-4">
                <form action="{{ route('tech.job.note', $job) }}" method="POST">
                    @csrf
                    <textarea name="tech_notes" rows="2" placeholder="Add notes about this job..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-[#5f7360] focus:border-purple-500">{{ $job->tech_notes }}</textarea>
                    <button type="submit" class="mt-2 px-4 py-2 bg-gray-600 text-white rounded-lg text-sm">
                        Save Notes
                    </button>
                </form>
            </div>
            @endif

            <!-- Back Link -->
            <div class="mt-6 text-center">
                <a href="{{ route('tech.dashboard') }}" class="text-[#4a5b4b] hover:underline">&larr; Back to Dashboard</a>
            </div>
        </div>
    </div>

    <script>
        // Get GPS location for clock in/out
        function getLocation(latInput, lngInput) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById(latInput).value = position.coords.latitude;
                    document.getElementById(lngInput).value = position.coords.longitude;
                }, function(error) {
                    console.log('Could not get location:', error);
                });
            }
        }

        // Try to get location on page load
        @if($job->status === 'scheduled')
            getLocation('clockInLat', 'clockInLng');
        @elseif($job->status === 'in_progress')
            getLocation('clockOutLat', 'clockOutLng');
        @endif
    </script>
</x-app-layout>
