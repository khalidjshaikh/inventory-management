<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Product') }}: {{ $product->name }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('products.update', $product) }}" method="POST">@csrf @method('PUT')
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-4"><label class="block text-sm font-medium text-gray-700">Name</label><input type="text" name="name" value="{{ old('name', $product->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>@error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                        <div class="mb-4"><label class="block text-sm font-medium text-gray-700">SKU</label><input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>@error('sku')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                        <div class="mb-4"><label class="block text-sm font-medium text-gray-700">Category</label><select name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><option value="">— Select —</option>@foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id', $product->category_id)==$cat->id ? 'selected':'' }}>{{ $cat->name }}</option>@endforeach</select></div>
                        <div class="mb-4"><label class="block text-sm font-medium text-gray-700">Supplier</label><select name="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><option value="">— Select —</option>@foreach($suppliers as $sup)<option value="{{ $sup->id }}" {{ old('supplier_id', $product->supplier_id)==$sup->id ? 'selected':'' }}>{{ $sup->name }}</option>@endforeach</select></div>
                        <div class="mb-4"><label class="block text-sm font-medium text-gray-700">Cost Price ($)</label><input type="number" step="0.01" min="0" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>@error('cost_price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                        <div class="mb-4"><label class="block text-sm font-medium text-gray-700">Selling Price ($)</label><input type="number" step="0.01" min="0" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>@error('selling_price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                        <div class="mb-4"><label class="block text-sm font-medium text-gray-700">Stock Quantity</label><input type="number" min="0" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>@error('stock_quantity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                        <div class="mb-4"><label class="block text-sm font-medium text-gray-700">Low Stock Threshold</label><input type="number" min="0" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>@error('low_stock_threshold')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                        <div class="mb-4"><label class="block text-sm font-medium text-gray-700">Unit</label><input type="text" name="unit" value="{{ old('unit', $product->unit) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>@error('unit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                        <div class="mb-4"><label class="inline-flex items-center"><input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"><span class="ml-2 text-sm text-gray-700">Active</span></label></div>
                    </div>
                    <div class="mb-4"><label class="block text-sm font-medium text-gray-700">Description</label><textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $product->description) }}</textarea></div>
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('products.index') }}" class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
