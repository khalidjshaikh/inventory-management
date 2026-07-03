<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Purchase Order') }}: {{ $purchaseOrder->order_number }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))<div class="p-4 mb-4 bg-green-50 text-green-800 rounded">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="p-4 mb-4 bg-red-50 text-red-800 rounded">{{ session('error') }}</div>@endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <dl class="grid grid-cols-2 gap-4">
                    <div><dt class="text-sm font-medium text-gray-500">Order #</dt><dd class="text-sm text-gray-900 font-bold">{{ $purchaseOrder->order_number }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Status</dt><dd><span class="px-2 py-1 text-xs rounded {{ $purchaseOrder->status === 'received' ? 'bg-green-100 text-green-800' : ($purchaseOrder->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($purchaseOrder->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">{{ ucfirst($purchaseOrder->status) }}</span></dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Supplier</dt><dd class="text-sm text-gray-900">{{ $purchaseOrder->supplier->name }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Order Date</dt><dd class="text-sm text-gray-900">{{ $purchaseOrder->order_date->format('Y-m-d') }}</dd></div>
                    @if($purchaseOrder->received_date)<div><dt class="text-sm font-medium text-gray-500">Received Date</dt><dd class="text-sm text-gray-900">{{ $purchaseOrder->received_date->format('Y-m-d') }}</dd></div>@endif
                    @if($purchaseOrder->notes)<div class="col-span-2"><dt class="text-sm font-medium text-gray-500">Notes</dt><dd class="text-sm text-gray-900">{{ $purchaseOrder->notes }}</dd></div>@endif
                </dl>
                <div class="mt-4 space-x-2">
                    @if(!in_array($purchaseOrder->status, ['received', 'cancelled']))
                    <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 inline-block">Edit</a>
                    @endif
                    <a href="{{ route('purchase-orders.index') }}" class="px-4 py-2 border rounded-md hover:bg-gray-50 inline-block">Back</a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Items</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead><tr><th class="text-left text-xs font-medium text-gray-500 uppercase">Product</th><th class="text-left text-xs font-medium text-gray-500 uppercase">SKU</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Ordered</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Received</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Unit Cost</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($purchaseOrder->items as $item)
                        <tr>
                            <td class="py-3">{{ $item->product->name }}</td>
                            <td class="py-3 text-sm">{{ $item->product->sku }}</td>
                            <td class="py-3 text-sm">{{ $item->quantity_ordered }}</td>
                            <td class="py-3 text-sm">{{ $item->quantity_received }}</td>
                            <td class="py-3 text-sm">${{ number_format($item->unit_cost, 2) }}</td>
                            <td class="py-3 text-sm">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-bold">
                            <td colspan="5" class="text-right py-3">Total:</td>
                            <td class="py-3">${{ number_format($purchaseOrder->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>

                @if(!in_array($purchaseOrder->status, ['received', 'cancelled']))
                <div class="mt-6 border-t pt-6">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Receive Items</h4>
                    <form action="{{ route('purchase-orders.receive', $purchaseOrder) }}" method="POST">@csrf
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead><tr><th class="text-left text-xs font-medium text-gray-500 uppercase">Product</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Ordered</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Previously Received</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Receive Now</th></tr></thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($purchaseOrder->items as $item)
                                <tr>
                                    <td class="py-2">{{ $item->product->name }}</td>
                                    <td class="py-2 text-sm">{{ $item->quantity_ordered }}</td>
                                    <td class="py-2 text-sm">{{ $item->quantity_received }}</td>
                                    <td class="py-2"><input type="number" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}" hidden><input type="number" name="items[{{ $loop->index }}][quantity_received]" min="0" max="{{ $item->quantity_ordered }}" value="{{ $item->quantity_ordered - $item->quantity_received }}" class="block w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4"><button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Receive Items</button></div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
