<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add New Employee
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.employees.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                                <option value="tech" {{ old('role') === 'tech' ? 'selected' : '' }}>Technician</option>
                                <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="pt-4 border-t">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <input type="password" name="password" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                <input type="password" name="password_confirmation" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                            </div>
                        </div>
                    </div>

                    <!-- Pay & Color -->
                    <div class="pt-4 border-t">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Hourly Rate</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">$</span>
                                    <input type="number" name="hourly_rate" value="{{ old('hourly_rate') }}" step="0.01" min="0"
                                           class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f7360] focus:border-purple-500">
                                </div>
                                @error('hourly_rate')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Calendar Color</label>
                                <div class="flex gap-2">
                                    @php
                                        $colors = ['#EF4444', '#F97316', '#EAB308', '#22C55E', '#14B8A6', '#3B82F6', '#8B5CF6', '#EC4899'];
                                    @endphp
                                    @foreach($colors as $color)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="color" value="{{ $color }}" class="sr-only peer" {{ old('color') === $color ? 'checked' : '' }}>
                                            <div class="w-8 h-8 rounded-full peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-gray-400" style="background-color: {{ $color }}"></div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="pt-4 border-t">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#4a5b4b] focus:ring-[#5f7360]">
                            <span class="ml-2 text-sm text-gray-600">Active (can be assigned jobs)</span>
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-4 pt-4 border-t">
                        <a href="{{ route('admin.employees.index') }}" class="px-4 py-2 text-gray-600 hover:underline">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-[#4a5b4b] text-white rounded-lg hover:bg-[#3d4a3e]">
                            Add Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
