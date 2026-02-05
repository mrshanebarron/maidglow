<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Job #{{ $job->id }}
            </h2>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $job->status_color }}-100 text-{{ $job->status_color }}-800">
                {{ ucfirst(str_replace('_', ' ', $job->status)) }}
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Schedule Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Schedule</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-500">Date</div>
                                <div class="font-medium">{{ $job->scheduled_date->format('l, F j, Y') }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Time</div>
                                <div class="font-medium">{{ \Carbon\Carbon::parse($job->scheduled_time)->format('g:i A') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Customer</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="font-medium text-lg">{{ $job->customer->name }}</div>
                                <div class="text-gray-500">{{ $job->customer->email }}</div>
                                <div class="text-gray-500">{{ $job->customer->phone }}</div>
                            </div>
                            <div class="pt-3 border-t">
                                <div class="text-sm text-gray-500">Address</div>
                                <div class="font-medium">{{ $job->customer->address }}</div>
                                <div>{{ $job->customer->city }}, {{ $job->customer->state }} {{ $job->customer->zip }}</div>
                            </div>
                            <div class="pt-3 border-t">
                                <div class="text-sm text-gray-500">Property Details</div>
                                <div class="flex gap-4 mt-1">
                                    <span>{{ $job->customer->bedrooms }} bed</span>
                                    <span>{{ $job->customer->bathrooms }} bath</span>
                                    <span>{{ number_format($job->customer->square_feet) }} sqft</span>
                                </div>
                                @if($job->customer->pets)
                                    <div class="text-sm text-amber-600 mt-1">Has pets</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Time Tracking Card -->
                    @if($job->status === 'in_progress' || $job->status === 'completed')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Time Tracking</h3>
                        @if($job->timeEntries->count() > 0)
                            <div class="space-y-3">
                                @foreach($job->timeEntries as $entry)
                                    <div class="flex justify-between items-center py-2 border-b last:border-0">
                                        <div>
                                            <div class="font-medium">{{ $entry->user->name }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $entry->clock_in->format('g:i A') }}
                                                @if($entry->clock_out)
                                                    - {{ $entry->clock_out->format('g:i A') }}
                                                @else
                                                    - <span class="text-green-600">Active</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            @if($entry->clock_out)
                                                <div class="font-semibold">{{ $entry->duration_formatted }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 pt-4 border-t flex justify-between">
                                <span class="font-medium">Total Time</span>
                                <span class="font-bold text-lg">{{ $job->formattedTimeWorked() }}</span>
                            </div>
                        @else
                            <p class="text-gray-500">No time entries yet.</p>
                        @endif
                    </div>
                    @endif

                    <!-- Notes -->
                    @if($job->notes)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Notes</h3>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $job->notes }}</p>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Service & Price -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Service</h3>
                        <div class="text-xl font-bold text-[#4a5b4b] mb-2">{{ $job->service->name }}</div>
                        <div class="text-3xl font-bold">${{ number_format($job->quoted_price, 2) }}</div>
                    </div>

                    <!-- Assigned Tech -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Assigned Technician</h3>
                        @if($job->assignedTech)
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold" style="background-color: {{ $job->assignedTech->color }}">
                                    {{ substr($job->assignedTech->name, 0, 1) }}
                                </div>
                                <div class="ml-3">
                                    <div class="font-medium">{{ $job->assignedTech->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $job->assignedTech->phone }}</div>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 mb-4">No technician assigned</p>
                        @endif

                        @if($job->status === 'scheduled')
                            <form action="{{ route('admin.jobs.assign', $job) }}" method="POST" class="mt-4">
                                @csrf
                                <select name="assigned_tech_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-2">
                                    <option value="">Select Tech</option>
                                    @foreach($techs as $tech)
                                        <option value="{{ $tech->id }}" {{ $job->assigned_to == $tech->id ? 'selected' : '' }}>
                                            {{ $tech->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="w-full px-4 py-2 bg-[#4a5b4b] text-white rounded-lg hover:bg-[#3d4a3e] text-sm">
                                    {{ $job->assignedTech ? 'Reassign' : 'Assign' }}
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Actions</h3>
                        <div class="space-y-2">
                            @if($job->status === 'scheduled')
                                <a href="{{ route('admin.jobs.edit', $job) }}" class="block w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-center">
                                    Edit Job
                                </a>
                            @endif
                            <a href="https://maps.google.com/?q={{ urlencode($job->customer->full_address) }}" target="_blank"
                               class="block w-full px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 text-center">
                                Open in Maps
                            </a>
                            <a href="tel:{{ $job->customer->phone }}"
                               class="block w-full px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 text-center">
                                Call Customer
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Link -->
            <div class="mt-6">
                <a href="{{ route('admin.jobs.index') }}" class="text-[#4a5b4b] hover:underline">&larr; Back to Jobs</a>
            </div>
        </div>
    </div>
</x-app-layout>
