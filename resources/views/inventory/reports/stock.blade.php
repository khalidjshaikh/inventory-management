<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Stock Report') }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Total Stock Value (Cost)</div>
                    <div class="text-2xl font-bold text-gray-900">${{ number_format($totalStockValue, 2) }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Total Stock Value (Selling)</div>
                    <div class="text-2xl font-bold text-gray-900">${{ number_format($totalSellingValue, 2) }}</div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase">Cost Price</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase">Selling Price</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase">Stock Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($products as $product)
                        <tr class="{{ $product->isLowStock() ? 'bg-red-50' : '' }}">
                            <td class="py-2"><a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:underline">{{ $product->name }}</a></td>
                            <td class="py-2 text-sm">{{ $product->category->name ?? '—' }}</td>
                            <td class="py-2 text-sm">{{ $product->supplier->name ?? '—' }}</td>
                            <td class="py-2 text-sm {{ $product->isLowStock() ? 'text-red-600 font-bold' : '' }}">{{ $product->stock_quantity }} {{ $product->unit }}</td>
                            <td class="py-2 text-sm">${{ number_format($product->cost_price, 2) }}</td>
                            <td class="py-2 text-sm">${{ number_format($product->selling_price, 2) }}</td>
                            <td class="py-2 text-sm">${{ number_format($product->stock_quantity * $product->cost_price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $products->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
