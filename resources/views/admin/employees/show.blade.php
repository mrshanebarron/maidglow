<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold mr-3" style="background-color: {{ $employee->color ?? '#6B7280' }}">
                    {{ substr($employee->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $employee->name }}
                    </h2>
                    <span class="text-sm text-gray-500">{{ ucfirst($employee->role) }}</span>
                </div>
            </div>
            <a href="{{ route('admin.employees.edit', $employee) }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                Edit Employee
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Employee Info -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm text-gray-500">Email</div>
                                <div class="font-medium">{{ $employee->email }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Phone</div>
                                <div class="font-medium">{{ $employee->phone ?? 'Not set' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Status</div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $employee->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $employee->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Performance</h3>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-500">Total Jobs</span>
                                    <span class="font-semibold">{{ $employee->assignedJobs->count() }}</span>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-500">Completed</span>
                                    <span class="font-semibold">{{ $employee->assignedJobs->where('status', 'completed')->count() }}</span>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-500">Completion Rate</span>
                                    @php
                                        $total = $employee->assignedJobs->count();
                                        $completed = $employee->assignedJobs->where('status', 'completed')->count();
                                        $rate = $total > 0 ? round(($completed / $total) * 100) : 0;
                                    @endphp
                                    <span class="font-semibold">{{ $rate }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $rate }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($employee->hourly_rate)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Pay Rate</h3>
                        <div class="text-3xl font-bold">${{ number_format($employee->hourly_rate, 2) }}</div>
                        <div class="text-sm text-gray-500">per hour</div>
                    </div>
                    @endif
                </div>

                <!-- Schedule & Jobs -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Upcoming Jobs -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b">
                            <h3 class="text-lg font-semibold">Upcoming Jobs</h3>
                        </div>
                        <div class="divide-y">
                            @forelse($employee->assignedJobs()->whereIn('status', ['scheduled', 'in_progress'])->orderBy('scheduled_date')->orderBy('scheduled_time')->take(5)->get() as $job)
                                <div class="p-4 hover:bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="font-medium">{{ $job->customer->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $job->service->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $job->customer->address }}, {{ $job->customer->city }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium">{{ $job->scheduled_date->format('M j') }}</div>
                                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($job->scheduled_time)->format('g:i A') }}</div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $job->status_color }}-100 text-{{ $job->status_color }}-800 mt-1">
                                                {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('admin.jobs.show', $job) }}" class="text-[#4a5b4b] hover:underline text-sm">View Details</a>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-500">
                                    No upcoming jobs scheduled.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Recent Completed -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b">
                            <h3 class="text-lg font-semibold">Recently Completed</h3>
                        </div>
                        <div class="divide-y">
                            @forelse($employee->assignedJobs()->where('status', 'completed')->orderBy('completed_at', 'desc')->take(5)->get() as $job)
                                <div class="p-4 hover:bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="font-medium">{{ $job->customer->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $job->service->name }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-green-600">{{ $job->formattedTimeWorked() }}</div>
                                            <div class="text-sm text-gray-500">{{ $job->completed_at?->format('M j, Y') }}</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-500">
                                    No completed jobs yet.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Link -->
            <div class="mt-6">
                <a href="{{ route('admin.employees.index') }}" class="text-[#4a5b4b] hover:underline">&larr; Back to Team</a>
            </div>
        </div>
    </div>
</x-app-layout>
