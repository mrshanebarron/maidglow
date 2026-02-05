<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Customer: {{ $customer->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.customers.update', $customer) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Contact Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name', $customer->name) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $customer->email) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="tel" name="phone" value="{{ old('phone', $customer->phone) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                    </div>

                    <!-- Address -->
                    <div class="pt-4 border-t">
                        <h3 class="font-semibold mb-4">Address</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Street Address</label>
                                <input type="text" name="address" value="{{ old('address', $customer->address) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                    <input type="text" name="city" value="{{ old('city', $customer->city) }}" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                                    <input type="text" name="state" value="{{ old('state', $customer->state) }}" required maxlength="2"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">ZIP</label>
                                    <input type="text" name="zip" value="{{ old('zip', $customer->zip) }}" required maxlength="10"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Property Details -->
                    <div class="pt-4 border-t">
                        <h3 class="font-semibold mb-4">Property Details</h3>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bedrooms</label>
                                <input type="number" name="bedrooms" value="{{ old('bedrooms', $customer->bedrooms) }}" min="0"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bathrooms</label>
                                <input type="number" name="bathrooms" value="{{ old('bathrooms', $customer->bathrooms) }}" min="0" step="0.5"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Square Feet</label>
                                <input type="number" name="square_feet" value="{{ old('square_feet', $customer->square_feet) }}" min="0"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="has_pets" value="1" {{ old('has_pets', $customer->has_pets) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-[#4a5b4b] focus:ring-[#5f7360]">
                                <span class="ml-2 text-sm text-gray-600">Has pets</span>
                            </label>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="pt-4 border-t">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500"
                                  placeholder="Access codes, special instructions, etc.">{{ old('notes', $customer->notes) }}</textarea>
                    </div>

                    <!-- Status -->
                    <div class="pt-4 border-t">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $customer->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#4a5b4b] focus:ring-[#5f7360]">
                            <span class="ml-2 text-sm text-gray-600">Active customer</span>
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-4 pt-4 border-t">
                        <a href="{{ route('admin.customers.show', $customer) }}" class="px-4 py-2 text-gray-600 hover:underline">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-[#4a5b4b] text-white rounded-lg hover:bg-[#3d4a3e]">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
