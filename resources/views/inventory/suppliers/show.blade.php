<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $supplier->name }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <dl class="grid grid-cols-2 gap-4">
                    <div><dt class="text-sm font-medium text-gray-500">Name</dt><dd class="text-sm text-gray-900">{{ $supplier->name }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Contact Person</dt><dd class="text-sm text-gray-900">{{ $supplier->contact_person ?? '—' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Email</dt><dd class="text-sm text-gray-900">{{ $supplier->email ?? '—' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Phone</dt><dd class="text-sm text-gray-900">{{ $supplier->phone ?? '—' }}</dd></div>
                    @if($supplier->address)<div class="col-span-2"><dt class="text-sm font-medium text-gray-500">Address</dt><dd class="text-sm text-gray-900">{{ $supplier->address }}</dd></div>@endif
                </dl>
                <div class="mt-4"><a href="{{ route('suppliers.edit', $supplier) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Edit</a></div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Products from {{ $supplier->name }}</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead><tr><th class="text-left text-xs font-medium text-gray-500 uppercase">Name</th><th class="text-left text-xs font-medium text-gray-500 uppercase">SKU</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Stock</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Price</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($supplier->products as $product)
                        <tr><td class="py-2"><a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:underline">{{ $product->name }}</a></td><td class="py-2 text-sm">{{ $product->sku }}</td><td class="py-2 text-sm {{ $product->isLowStock() ? 'text-red-600 font-bold' : '' }}">{{ $product->stock_quantity }}</td><td class="py-2 text-sm">${{ number_format($product->selling_price, 2) }}</td></tr>
                        @empty
                        <tr><td colspan="4" class="py-4 text-center text-gray-500">No products from this supplier.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Purchase Orders</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead><tr><th class="text-left text-xs font-medium text-gray-500 uppercase">Order #</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Date</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Status</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Total</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($supplier->purchaseOrders()->latest()->get() as $po)
                        <tr><td class="py-2"><a href="{{ route('purchase-orders.show', $po) }}" class="text-blue-600 hover:underline">{{ $po->order_number }}</a></td><td class="py-2 text-sm">{{ $po->order_date->format('Y-m-d') }}</td><td class="py-2"><span class="px-2 py-1 text-xs rounded {{ $po->status === 'received' ? 'bg-green-100 text-green-800' : ($po->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">{{ ucfirst($po->status) }}</span></td><td class="py-2 text-sm">${{ number_format($po->total_amount, 2) }}</td></tr>
                        @empty
                        <tr><td colspan="4" class="py-4 text-center text-gray-500">No purchase orders.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
