<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Schedule New Job
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.jobs.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <!-- Customer Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                        <select name="customer_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} - {{ $customer->address }}, {{ $customer->city }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Service Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                        <select name="service_id" id="service_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                            <option value="">Select Service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" data-base="{{ $service->base_price }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} - Starting at ${{ number_format($service->base_price, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date & Time -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" name="scheduled_date" value="{{ old('scheduled_date', date('Y-m-d')) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                            @error('scheduled_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                            <select name="scheduled_time" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                                @for($hour = 7; $hour <= 18; $hour++)
                                    @foreach(['00', '30'] as $min)
                                        @php $time = sprintf('%02d:%s', $hour, $min); @endphp
                                        <option value="{{ $time }}" {{ old('scheduled_time', '09:00') == $time ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::parse($time)->format('g:i A') }}
                                        </option>
                                    @endforeach
                                @endfor
                            </select>
                            @error('scheduled_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Assign Tech -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assign Technician (Optional)</label>
                        <select name="assigned_to" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                            <option value="">Unassigned</option>
                            @foreach($techs as $tech)
                                <option value="{{ $tech->id }}" {{ old('assigned_to') == $tech->id ? 'selected' : '' }}>
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
                            <input type="number" name="quoted_price" id="quoted_price" step="0.01" value="{{ old('quoted_price') }}" required
                                   class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Price will auto-fill based on customer property and service</p>
                        @error('quoted_price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500"
                                  placeholder="Special instructions, access codes, etc.">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-4 pt-4 border-t">
                        <a href="{{ route('admin.jobs.index') }}" class="px-4 py-2 text-gray-600 hover:underline">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-[#4a5b4b] text-white rounded-lg hover:bg-[#3d4a3e]">
                            Schedule Job
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-calculate price when customer or service changes
        const customerSelect = document.querySelector('[name="customer_id"]');
        const serviceSelect = document.getElementById('service_id');
        const priceInput = document.getElementById('quoted_price');

        function updatePrice() {
            const customerId = customerSelect.value;
            const serviceId = serviceSelect.value;

            if (customerId && serviceId) {
                fetch(`{{ route('admin.jobs.getPrice') }}?customer_id=${customerId}&service_id=${serviceId}`)
                    .then(r => r.json())
                    .then(data => {
                        priceInput.value = data.price;
                    });
            }
        }

        customerSelect.addEventListener('change', updatePrice);
        serviceSelect.addEventListener('change', updatePrice);
    </script>
</x-app-layout>
