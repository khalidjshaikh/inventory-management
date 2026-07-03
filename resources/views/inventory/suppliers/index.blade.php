<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Suppliers') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">All Suppliers</h3>
                    <a href="{{ route('suppliers.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">+ New Supplier</a>
                </div>
                @if(session('success')) <div class="p-4 bg-green-50 text-green-800 border-b border-green-200">{{ session('success') }}</div> @endif
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Products</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($suppliers as $supplier)
                            <tr>
                                <td class="py-3"><a href="{{ route('suppliers.show', $supplier) }}" class="text-blue-600 hover:underline font-medium">{{ $supplier->name }}</a></td>
                                <td class="py-3 text-sm">{{ $supplier->contact_person ?? '—' }}</td>
                                <td class="py-3 text-sm">{{ $supplier->email ?? '—' }}</td>
                                <td class="py-3 text-sm">{{ $supplier->products_count }}</td>
                                <td class="py-3 text-sm space-x-2">
                                    <a href="{{ route('suppliers.edit', $supplier) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline" onsubmit="return confirm('Delete this supplier?')">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:underline">Delete</button></form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $suppliers->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
