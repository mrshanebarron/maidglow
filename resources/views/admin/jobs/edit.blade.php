<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Job #{{ $job->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.jobs.update', $job) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Customer Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                        <select name="customer_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ $job->customer_id == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} - {{ $customer->address }}, {{ $customer->city }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Service Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                        <select name="service_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ $job->service_id == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} - Starting at ${{ number_format($service->base_price, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date & Time -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" name="scheduled_date" value="{{ $job->scheduled_date->format('Y-m-d') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                            <select name="scheduled_time" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                                @for($hour = 7; $hour <= 18; $hour++)
                                    @foreach(['00', '30'] as $min)
                                        @php $time = sprintf('%02d:%s', $hour, $min); @endphp
                                        <option value="{{ $time }}" {{ substr($job->scheduled_time, 0, 5) == $time ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::parse($time)->format('g:i A') }}
                                        </option>
                                    @endforeach
                                @endfor
                            </select>
                        </div>
                    </div>

                    <!-- Assign Tech -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Technician</label>
                        <select name="assigned_to" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                            <option value="">Unassigned</option>
                            @foreach($techs as $tech)
                                <option value="{{ $tech->id }}" {{ $job->assigned_to == $tech->id ? 'selected' : '' }}>
                                    {{ $tech->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Quoted Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quoted Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" name="quoted_price" step="0.01" value="{{ $job->quoted_price }}" required
                                   class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                            <option value="scheduled" {{ $job->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="in_progress" {{ $job->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $job->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $job->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="no_show" {{ $job->status === 'no_show' ? 'selected' : '' }}>No Show</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500"
                                  placeholder="Special instructions, access codes, etc.">{{ $job->notes }}</textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between items-center pt-4 border-t">
                        <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this job?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-red-600 hover:underline">Delete Job</button>
                        </form>
                        <div class="flex gap-4">
                            <a href="{{ route('admin.jobs.show', $job) }}" class="px-4 py-2 text-gray-600 hover:underline">Cancel</a>
                            <button type="submit" class="px-6 py-2 bg-[#4a5b4b] text-white rounded-lg hover:bg-[#3d4a3e]">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
