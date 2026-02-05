<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Team
            </h2>
            <a href="{{ route('admin.employees.create') }}" class="px-4 py-2 bg-[#4a5b4b] text-white rounded-lg hover:bg-[#3d4a3e]">
                Add Employee
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($employees as $employee)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg" style="background-color: {{ $employee->color ?? '#6B7280' }}">
                                        {{ substr($employee->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-semibold">{{ $employee->name }}</div>
                                        <div class="text-sm text-gray-500">{{ ucfirst($employee->role) }}</div>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $employee->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $employee->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <div class="text-gray-500">Total Jobs</div>
                                    <div class="font-semibold">{{ $employee->total_jobs }}</div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Completed</div>
                                    <div class="font-semibold">{{ $employee->completed_jobs }}</div>
                                </div>
                            </div>

                            @if($employee->hourly_rate)
                                <div class="mt-3 text-sm">
                                    <span class="text-gray-500">Rate:</span>
                                    <span class="font-semibold">${{ number_format($employee->hourly_rate, 2) }}/hr</span>
                                </div>
                            @endif

                            <div class="mt-4 pt-4 border-t flex justify-between">
                                <a href="{{ route('admin.employees.show', $employee) }}" class="text-[#4a5b4b] hover:underline text-sm">View Details</a>
                                <a href="{{ route('admin.employees.edit', $employee) }}" class="text-gray-600 hover:underline text-sm">Edit</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
