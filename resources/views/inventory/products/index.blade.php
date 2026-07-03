<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Products') }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">All Products</h3>
                    <a href="{{ route('products.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">+ New Product</a>
                </div>
                @if(session('success'))<div class="p-4 bg-green-50 text-green-800 border-b border-green-200">{{ session('success') }}</div>@endif
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Name / SKU</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($products as $product)
                            <tr class="{{ $product->isLowStock() ? 'bg-red-50' : '' }}">
                                <td class="py-3">
                                    <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:underline font-medium">{{ $product->name }}</a>
                                    <div class="text-xs text-gray-500">SKU: {{ $product->sku }}</div>
                                </td>
                                <td class="py-3 text-sm">{{ $product->category->name ?? '—' }}</td>
                                <td class="py-3 text-sm {{ $product->isLowStock() ? 'text-red-600 font-bold' : '' }}">{{ $product->stock_quantity }} {{ $product->unit }}</td>
                                <td class="py-3 text-sm">${{ number_format($product->cost_price, 2) }}</td>
                                <td class="py-3 text-sm">${{ number_format($product->selling_price, 2) }}</td>
                                <td class="py-3 text-sm space-x-2">
                                    <a href="{{ route('products.edit', $product) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <a href="{{ route('products.barcode', $product) }}" class="text-gray-600 hover:underline" target="_blank">Barcode</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Delete this product?')">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:underline">Delete</button></form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $products->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
