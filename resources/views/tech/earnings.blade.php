<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Earnings
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-lg mx-auto px-4">
            <!-- Earnings Cards -->
            <div class="grid grid-cols-1 gap-4 mb-6">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl p-6 text-white">
                    <div class="text-sm opacity-80">This Week</div>
                    <div class="text-4xl font-bold">${{ number_format($thisWeekEarnings, 2) }}</div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white rounded-xl shadow-sm p-5">
                        <div class="text-sm text-gray-500">Last Week</div>
                        <div class="text-2xl font-bold text-gray-800">${{ number_format($lastWeekEarnings, 2) }}</div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-5">
                        <div class="text-sm text-gray-500">This Month</div>
                        <div class="text-2xl font-bold text-gray-800">${{ number_format($thisMonthEarnings, 2) }}</div>
                    </div>
                </div>
            </div>

            <!-- Recent Jobs -->
            <h3 class="text-lg font-semibold mb-3">Recent Completed Jobs</h3>

            @forelse($recentJobs as $job)
                <div class="bg-white rounded-xl shadow-sm p-4 mb-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="font-medium">{{ $job->customer->name }}</div>
                            <div class="text-sm text-gray-500">{{ $job->service->name }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold text-green-600">{{ $job->formattedTimeWorked() }}</div>
                            <div class="text-sm text-gray-500">{{ $job->completed_at?->format('M j') }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl p-8 text-center">
                    <p class="text-gray-500">No completed jobs yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
