<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $customer->name }}
            </h2>
            <a href="{{ route('admin.customers.edit', $customer) }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                Edit Customer
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Customer Info -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm text-gray-500">Email</div>
                                <div class="font-medium">{{ $customer->email }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Phone</div>
                                <div class="font-medium">{{ $customer->phone }}</div>
                            </div>
                            <div class="pt-3 border-t">
                                <div class="text-sm text-gray-500">Address</div>
                                <div class="font-medium">{{ $customer->address }}</div>
                                <div>{{ $customer->city }}, {{ $customer->state }} {{ $customer->zip }}</div>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t flex gap-2">
                            <a href="tel:{{ $customer->phone }}" class="flex-1 px-3 py-2 bg-green-100 text-green-700 rounded-lg text-center text-sm hover:bg-green-200">
                                Call
                            </a>
                            <a href="mailto:{{ $customer->email }}" class="flex-1 px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-center text-sm hover:bg-blue-200">
                                Email
                            </a>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
                        <h3 class="text-lg font-semibold mb-4">Property Details</h3>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold">{{ $customer->bedrooms }}</div>
                                <div class="text-sm text-gray-500">Bedrooms</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold">{{ $customer->bathrooms }}</div>
                                <div class="text-sm text-gray-500">Bathrooms</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold">{{ number_format($customer->square_feet) }}</div>
                                <div class="text-sm text-gray-500">Sq Ft</div>
                            </div>
                        </div>
                        @if($customer->pets)
                            <div class="mt-4 pt-4 border-t">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    Has Pets
                                </span>
                            </div>
                        @endif
                        @if($customer->notes)
                            <div class="mt-4 pt-4 border-t">
                                <div class="text-sm text-gray-500">Notes</div>
                                <p class="text-gray-700 mt-1">{{ $customer->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
                        <h3 class="text-lg font-semibold mb-4">Statistics</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Total Jobs</span>
                                <span class="font-semibold">{{ $customer->cleaningJobs->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Completed</span>
                                <span class="font-semibold">{{ $customer->cleaningJobs->where('status', 'completed')->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Total Revenue</span>
                                <span class="font-semibold">${{ number_format($customer->cleaningJobs->where('status', 'completed')->sum('quoted_price'), 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Customer Since</span>
                                <span class="font-semibold">{{ $customer->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job History -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b flex justify-between items-center">
                            <h3 class="text-lg font-semibold">Job History</h3>
                            <a href="{{ route('admin.jobs.create', ['customer_id' => $customer->id]) }}" class="px-4 py-2 bg-[#4a5b4b] text-white rounded-lg hover:bg-[#3d4a3e] text-sm">
                                Schedule Job
                            </a>
                        </div>
                        <div class="divide-y">
                            @forelse($customer->cleaningJobs()->orderBy('scheduled_date', 'desc')->get() as $job)
                                <div class="p-4 hover:bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="font-medium">{{ $job->service->name }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $job->scheduled_date->format('M j, Y') }} at {{ \Carbon\Carbon::parse($job->scheduled_time)->format('g:i A') }}
                                            </div>
                                            @if($job->assignedTech)
                                                <div class="text-sm text-gray-500 mt-1">
                                                    Tech: {{ $job->assignedTech->name }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $job->status_color }}-100 text-{{ $job->status_color }}-800">
                                                {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                                            </span>
                                            <div class="font-semibold mt-1">${{ number_format($job->quoted_price, 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('admin.jobs.show', $job) }}" class="text-[#4a5b4b] hover:underline text-sm">View Details</a>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-500">
                                    No jobs scheduled yet.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Link -->
            <div class="mt-6">
                <a href="{{ route('admin.customers.index') }}" class="text-[#4a5b4b] hover:underline">&larr; Back to Customers</a>
            </div>
        </div>
    </div>
</x-app-layout>
