<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
    </x-slot>

    <!-- Stats -->
    <div class="mb-8">
        <dl class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Today's Jobs</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $todaysJobs->count() }}</dd>
            </div>
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">In Progress</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-[#4a5b4b]">{{ $todaysJobs->where('status', 'in_progress')->count() }}</dd>
            </div>
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Completed Today</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-green-600">{{ $todaysJobs->where('status', 'completed')->count() }}</dd>
            </div>
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Today's Revenue</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">${{ number_format($todaysJobs->where('status', 'completed')->sum('quoted_price'), 0) }}</dd>
            </div>
        </dl>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <!-- Today's Jobs -->
        <div class="lg:col-span-2">
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="text-base font-semibold leading-6 text-gray-900">Today's Schedule</h3>
                        <a href="{{ route('admin.jobs.create') }}" class="rounded-md bg-[#4a5b4b] px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#5f7360]">
                            Schedule Job
                        </a>
                    </div>
                </div>
                <ul role="list" class="divide-y divide-gray-200">
                    @forelse($todaysJobs as $job)
                        <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-x-4">
                                    <div class="flex-shrink-0">
                                        @if($job->assignedTech)
                                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full" style="background-color: {{ $job->assignedTech->color }}">
                                                <span class="text-sm font-medium leading-none text-white">{{ substr($job->assignedTech->name, 0, 1) }}</span>
                                            </span>
                                        @else
                                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-300">
                                                <span class="text-sm font-medium leading-none text-gray-600">?</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $job->customer->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $job->service->name }} · {{ $job->customer->city }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-x-4">
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($job->scheduled_time)->format('g:i A') }}</p>
                                        <p class="text-sm text-gray-500">${{ number_format($job->quoted_price, 0) }}</p>
                                    </div>
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                        {{ $job->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $job->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $job->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                                    </span>
                                    <a href="{{ route('admin.jobs.show', $job) }}" class="text-[#4a5b4b] hover:text-[#333d34]">
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="px-4 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900">No jobs today</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by scheduling a new job.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.jobs.create') }}" class="inline-flex items-center rounded-md bg-[#4a5b4b] px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#5f7360]">
                                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                    </svg>
                                    Schedule Job
                                </a>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Team Status -->
        <div>
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
                    <h3 class="text-base font-semibold leading-6 text-gray-900">Team Status</h3>
                </div>
                <ul role="list" class="divide-y divide-gray-200">
                    @foreach($techs as $tech)
                        @php
                            $techJob = $todaysJobs->where('assigned_to', $tech->id)->first();
                            $isWorking = $techJob && $techJob->status === 'in_progress';
                            $completedToday = $todaysJobs->where('assigned_to', $tech->id)->where('status', 'completed')->count();
                            $totalToday = $todaysJobs->where('assigned_to', $tech->id)->count();
                        @endphp
                        <li class="px-4 py-4 sm:px-6">
                            <div class="flex items-center gap-x-4">
                                <div class="relative">
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full" style="background-color: {{ $tech->color }}">
                                        <span class="text-sm font-medium leading-none text-white">{{ substr($tech->name, 0, 1) }}</span>
                                    </span>
                                    @if($isWorking)
                                        <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full bg-green-400 ring-2 ring-white"></span>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $tech->name }}</p>
                                    <p class="text-sm text-gray-500">
                                        @if($isWorking)
                                            Working: {{ $techJob->customer->name }}
                                        @elseif($totalToday > 0)
                                            {{ $completedToday }}/{{ $totalToday }} jobs done
                                        @else
                                            No jobs today
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Upcoming Jobs -->
            <div class="mt-8 overflow-hidden rounded-lg bg-white shadow">
                <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
                    <h3 class="text-base font-semibold leading-6 text-gray-900">Upcoming Jobs</h3>
                </div>
                <ul role="list" class="divide-y divide-gray-200">
                    @forelse($upcomingJobs->take(5) as $job)
                        <li class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $job->customer->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $job->service->name }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ $job->scheduled_date->format('M j') }}</p>
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($job->scheduled_time)->format('g:i A') }}</p>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="px-4 py-8 text-center text-sm text-gray-500">
                            No upcoming jobs scheduled.
                        </li>
                    @endforelse
                </ul>
                @if($upcomingJobs->count() > 5)
                    <div class="border-t border-gray-200 px-4 py-4 sm:px-6">
                        <a href="{{ route('admin.jobs.index') }}" class="text-sm font-medium text-[#4a5b4b] hover:text-[#5f7360]">
                            View all jobs →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
