<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Purchase Orders') }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">All Purchase Orders</h3>
                    <a href="{{ route('purchase-orders.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">+ New PO</a>
                </div>
                @if(session('success'))<div class="p-4 bg-green-50 text-green-800 border-b border-green-200">{{ session('success') }}</div>@endif
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($purchaseOrders as $po)
                            <tr>
                                <td class="py-3"><a href="{{ route('purchase-orders.show', $po) }}" class="text-blue-600 hover:underline font-medium">{{ $po->order_number }}</a></td>
                                <td class="py-3 text-sm">{{ $po->supplier->name }}</td>
                                <td class="py-3 text-sm">{{ $po->order_date->format('Y-m-d') }}</td>
                                <td class="py-3 text-sm">{{ $po->items_count }}</td>
                                <td class="py-3">
                                    <span class="px-2 py-1 text-xs rounded {{ $po->status === 'received' ? 'bg-green-100 text-green-800' : ($po->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($po->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                                        {{ ucfirst($po->status) }}
                                    </span>
                                </td>
                                <td class="py-3 text-sm">${{ number_format($po->total_amount, 2) }}</td>
                                <td class="py-3 text-sm space-x-2">
                                    <a href="{{ route('purchase-orders.show', $po) }}" class="text-blue-600 hover:underline">View</a>
                                    @if(!in_array($po->status, ['received', 'cancelled']))
                                    <a href="{{ route('purchase-orders.edit', $po) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('purchase-orders.destroy', $po) }}" method="POST" class="inline" onsubmit="return confirm('Delete this order?')">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:underline">Delete</button></form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $purchaseOrders->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
