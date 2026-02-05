<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Customers
            </h2>
            <a href="{{ route('admin.customers.create') }}" class="px-4 py-2 bg-[#4a5b4b] text-white rounded-lg hover:bg-[#3d4a3e]">
                Add Customer
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jobs</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($customers as $customer)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-medium">{{ $customer->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $customer->email }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">{{ $customer->city }}, {{ $customer->state }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $customer->phone }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $customer->cleaning_jobs_count }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $customer->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('admin.customers.show', $customer) }}" class="text-[#4a5b4b] hover:underline">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
