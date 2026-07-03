<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $category->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="text-sm text-gray-900">{{ $category->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd><span class="px-2 py-1 text-xs rounded {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></dd>
                    </div>
                    @if($category->description)
                    <div class="col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="text-sm text-gray-900">{{ $category->description }}</dd>
                    </div>
                    @endif
                </dl>
                <div class="mt-4 space-x-2">
                    <a href="{{ route('categories.edit', $category) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Edit</a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Products in this Category</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($products as $product)
                            <tr>
                                <td class="py-2"><a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:underline">{{ $product->name }}</a></td>
                                <td class="py-2 text-sm">{{ $product->sku }}</td>
                                <td class="py-2 text-sm">{{ $product->stock_quantity }}</td>
                                <td class="py-2 text-sm">${{ number_format($product->selling_price, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-4 text-center text-gray-500">No products in this category.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $products->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
