<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $product->name }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))<div class="p-4 mb-4 bg-green-50 text-green-800 rounded">{{ session('success') }}</div>@endif
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <dl class="grid grid-cols-2 gap-4">
                        <div><dt class="text-sm font-medium text-gray-500">Name</dt><dd class="text-sm text-gray-900">{{ $product->name }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">SKU</dt><dd class="text-sm text-gray-900">{{ $product->sku }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">Barcode</dt><dd class="text-sm text-gray-900 font-mono">{{ $product->barcode }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">Unit</dt><dd class="text-sm text-gray-900">{{ $product->unit }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">Category</dt><dd class="text-sm text-gray-900">{{ $product->category->name ?? '—' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">Supplier</dt><dd class="text-sm text-gray-900">{{ $product->supplier->name ?? '—' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">Cost Price</dt><dd class="text-sm text-gray-900">${{ number_format($product->cost_price, 2) }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">Selling Price</dt><dd class="text-sm text-gray-900">${{ number_format($product->selling_price, 2) }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">Stock</dt><dd class="text-sm {{ $product->isLowStock() ? 'text-red-600 font-bold' : 'text-green-600 font-bold' }}">{{ $product->stock_quantity }} {{ $product->unit }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">Low Stock Threshold</dt><dd class="text-sm text-gray-900">{{ $product->low_stock_threshold }}</dd></div>
                        <div class="col-span-2"><dt class="text-sm font-medium text-gray-500">Description</dt><dd class="text-sm text-gray-900">{{ $product->description ?? '—' }}</dd></div>
                    </dl>
                    <div class="mt-4 space-x-2">
                        <a href="{{ route('products.edit', $product) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 inline-block">Edit</a>
                        <a href="{{ route('products.barcode', $product) }}" class="px-4 py-2 border rounded-md hover:bg-gray-50 inline-block" target="_blank">View Barcode</a>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Barcode</h3>
                    <div class="text-center bg-white p-4 border rounded">
                        <img src="{{ route('products.barcode', $product) }}" alt="Barcode" class="mx-auto" style="max-width: 100%;">
                        <p class="mt-2 text-xs text-gray-500 font-mono">{{ $product->barcode }}</p>
                        <div class="mt-4 text-sm space-y-1">
                            <div><strong>SKU:</strong> {{ $product->sku }}</div>
                            <div><strong>Name:</strong> {{ $product->name }}</div>
                            <div><strong>Price:</strong> ${{ number_format($product->selling_price, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Stock History</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead><tr><th class="text-left text-xs font-medium text-gray-500 uppercase">Date</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Type</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Change</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Notes</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($product->stockHistories as $history)
                        <tr>
                            <td class="py-2 text-sm">{{ $history->created_at->format('Y-m-d H:i') }}</td>
                            <td class="py-2"><span class="px-2 py-1 text-xs rounded {{ $history->type === 'purchase' ? 'bg-green-100 text-green-800' : ($history->type === 'sale' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">{{ ucfirst($history->type) }}</span></td>
                            <td class="py-2 text-sm {{ $history->quantity_change > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $history->quantity_change > 0 ? '+' : '' }}{{ $history->quantity_change }}</td>
                            <td class="py-2 text-sm text-gray-500">{{ $history->notes ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-4 text-center text-gray-500">No stock history yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-2"><a href="{{ route('stock-history.index', ['product_id' => $product->id]) }}" class="text-blue-600 hover:underline text-sm">View all history →</a></div>
            </div>
        </div>
    </div>
</x-app-layout>
