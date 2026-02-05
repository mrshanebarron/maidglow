<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-2xl font-semibold text-gray-900">Jobs</h1>
            <a href="{{ route('admin.jobs.create') }}" class="rounded-md bg-[#4a5b4b] px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#5f7360]">
                Schedule Job
            </a>
        </div>
    </x-slot>

    <!-- Filters -->
    <div class="mb-6 overflow-hidden rounded-lg bg-white shadow">
        <form action="" method="GET" class="p-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div>
                    <label for="status" class="block text-sm font-medium leading-6 text-gray-900">Status</label>
                    <select id="status" name="status" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-purple-600 sm:text-sm sm:leading-6">
                        <option value="">All</option>
                        <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label for="tech" class="block text-sm font-medium leading-6 text-gray-900">Technician</label>
                    <select id="tech" name="tech" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-purple-600 sm:text-sm sm:leading-6">
                        <option value="">All</option>
                        @foreach($techs as $tech)
                            <option value="{{ $tech->id }}" {{ request('tech') == $tech->id ? 'selected' : '' }}>{{ $tech->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="date" class="block text-sm font-medium leading-6 text-gray-900">Date</label>
                    <input type="date" id="date" name="date" value="{{ request('date') }}" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-purple-600 sm:text-sm sm:leading-6">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500">Filter</button>
                    <a href="{{ route('admin.jobs.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-lg bg-white shadow">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Date/Time</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Customer</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Service</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Technician</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Price</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($jobs as $job)
                    <tr class="hover:bg-gray-50">
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 sm:pl-6">
                            <div class="text-sm font-medium text-gray-900">{{ $job->scheduled_date->format('M j, Y') }}</div>
                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($job->scheduled_time)->format('g:i A') }}</div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $job->customer->name }}</div>
                            <div class="text-sm text-gray-500">{{ $job->customer->city }}</div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $job->service->name }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            @if($job->assignedTech)
                                <div class="flex items-center">
                                    <span class="h-2 w-2 rounded-full mr-2" style="background-color: {{ $job->assignedTech->color }}"></span>
                                    <span class="text-gray-900">{{ $job->assignedTech->name }}</span>
                                </div>
                            @else
                                <span class="text-gray-400">Unassigned</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900">${{ number_format($job->quoted_price, 2) }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                {{ $job->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $job->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $job->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $job->status === 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $job->status === 'no_show' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                            </span>
                        </td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <a href="{{ route('admin.jobs.show', $job) }}" class="text-[#4a5b4b] hover:text-[#333d34]">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900">No jobs found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or schedule a new job.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($jobs->hasPages())
            <div class="border-t border-gray-200 px-4 py-4 sm:px-6">
                {{ $jobs->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
