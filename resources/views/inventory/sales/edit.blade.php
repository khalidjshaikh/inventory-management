<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Sale') }}: {{ $sale->sale_number }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form id="saleForm" action="{{ route('sales.update', $sale) }}" method="POST">@csrf @method('PUT')
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Sale Date</label>
                        <input type="date" name="sale_date" value="{{ old('sale_date', $sale->sale_date->format('Y-m-d')) }}" class="mt-1 block w-64 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('sale_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4"><label class="block text-sm font-medium text-gray-700">Notes</label><textarea name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $sale->notes) }}</textarea></div>

                    <h4 class="text-md font-medium text-gray-900 mb-3">Items</h4>
                    @error('items')<p class="text-red-500 text-xs mb-2">{{ $message }}</p>@enderror
                    <table class="min-w-full divide-y divide-gray-200 mb-4">
                        <thead><tr><th class="text-left text-xs font-medium text-gray-500 uppercase">Product</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Qty</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th></tr></thead>
                        <tbody id="itemsBody">
                            @foreach($sale->items as $idx => $item)
                            <tr class="item-row">
                                <td class="py-2 pr-2">
                                    <select name="items[{{ $idx }}][product_id]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                                        <option value="">— Select —</option>
                                        @foreach($products as $p)
                                        <option value="{{ $p->id }}" data-price="{{ $p->selling_price }}" {{ $item->product_id == $p->id ? 'selected' : '' }}>{{ $p->name }} ({{ $p->sku }}) — Stock: {{ $p->stock_quantity }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-2 pr-2"><input type="number" name="items[{{ $idx }}][quantity]" min="1" value="{{ $item->quantity }}" class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm qty" required></td>
                                <td class="py-2 pr-2"><input type="number" step="0.01" min="0" name="items[{{ $idx }}][unit_price]" value="{{ $item->unit_price }}" class="block w-28 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm price" required></td>
                                <td class="py-2 text-sm subtotal">${{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="flex justify-between items-center border-t pt-4">
                        <div class="text-lg font-bold">Total: $<span id="totalAmount">{{ number_format($sale->total_amount, 2) }}</span></div>
                        <div class="space-x-3">
                            <a href="{{ route('sales.show', $sale) }}" class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Sale</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
function calcTotal() { let total=0; document.querySelectorAll('.item-row').forEach(row=>{const qty=parseFloat(row.querySelector('.qty').value)||0;const price=parseFloat(row.querySelector('.price').value)||0;const sub=qty*price;row.querySelector('.subtotal').textContent='$'+sub.toFixed(2);total+=sub;}); document.getElementById('totalAmount').textContent=total.toFixed(2);}
document.querySelectorAll('.qty,.price').forEach(el=>el.addEventListener('input',calcTotal));
document.querySelectorAll('select[name$="[product_id]"]').forEach(sel=>{sel.addEventListener('change',function(e){const opt=e.target.selectedOptions[0];const row=e.target.closest('.item-row');row.querySelector('.price').value=opt?opt.dataset.price:0;calcTotal();});});
calcTotal();
</script>
