<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Sale') }}: {{ $sale->sale_number }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))<div class="p-4 mb-4 bg-green-50 text-green-800 rounded">{{ session('success') }}</div>@endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <dl class="grid grid-cols-2 gap-4">
                    <div><dt class="text-sm font-medium text-gray-500">Sale #</dt><dd class="text-sm text-gray-900 font-bold">{{ $sale->sale_number }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Date</dt><dd class="text-sm text-gray-900">{{ $sale->sale_date->format('Y-m-d') }}</dd></div>
                    @if($sale->notes)<div class="col-span-2"><dt class="text-sm font-medium text-gray-500">Notes</dt><dd class="text-sm text-gray-900">{{ $sale->notes }}</dd></div>@endif
                </dl>
                <div class="mt-4"><a href="{{ route('sales.index') }}" class="px-4 py-2 border rounded-md hover:bg-gray-50 inline-block">Back</a></div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Items</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead><tr><th class="text-left text-xs font-medium text-gray-500 uppercase">Product</th><th class="text-left text-xs font-medium text-gray-500 uppercase">SKU</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Qty</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($sale->items as $item)
                        <tr>
                            <td class="py-3">{{ $item->product->name }}</td>
                            <td class="py-3 text-sm">{{ $item->product->sku }}</td>
                            <td class="py-3 text-sm">{{ $item->quantity }}</td>
                            <td class="py-3 text-sm">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-3 text-sm">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-bold"><td colspan="4" class="text-right py-3">Total:</td><td class="py-3">${{ number_format($sale->total_amount, 2) }}</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
