<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Purchase Order') }}: {{ $purchaseOrder->order_number }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form id="poForm" action="{{ route('purchase-orders.update', $purchaseOrder) }}" method="POST">@csrf @method('PUT')
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div><label class="block text-sm font-medium text-gray-700">Supplier</label><select name="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required><option value="">— Select —</option>@foreach($suppliers as $sup)<option value="{{ $sup->id }}" {{ old('supplier_id', $purchaseOrder->supplier_id)==$sup->id ? 'selected':'' }}>{{ $sup->name }}</option>@endforeach</select></div>
                        <div><label class="block text-sm font-medium text-gray-700">Order Date</label><input type="date" name="order_date" value="{{ old('order_date', $purchaseOrder->order_date->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></div>
                    </div>
                    <div class="mb-4"><label class="block text-sm font-medium text-gray-700">Notes</label><textarea name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $purchaseOrder->notes) }}</textarea></div>

                    <h4 class="text-md font-medium text-gray-900 mb-3">Items</h4>
                    <table class="min-w-full divide-y divide-gray-200 mb-4"><thead><tr><th class="text-left text-xs font-medium text-gray-500 uppercase">Product</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Qty</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Unit Cost</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th></tr></thead>
                        <tbody>
                            @foreach($purchaseOrder->items as $idx => $item)
                            <tr class="item-row">
                                <td class="py-2 pr-2">
                                    <select name="items[{{ $idx }}][product_id]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                                        <option value="">— Select —</option>
                                        @foreach($products as $p)<option value="{{ $p->id }}" {{ $item->product_id == $p->id ? 'selected' : '' }}>{{ $p->name }} ({{ $p->sku }})</option>@endforeach
                                    </select>
                                </td>
                                <td class="py-2 pr-2"><input type="number" name="items[{{ $idx }}][quantity_ordered]" min="1" value="{{ $item->quantity_ordered }}" class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm qty" required></td>
                                <td class="py-2 pr-2"><input type="number" step="0.01" min="0" name="items[{{ $idx }}][unit_cost]" value="{{ $item->unit_cost }}" class="block w-28 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm cost" required></td>
                                <td class="py-2 text-sm subtotal">${{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="flex justify-between items-center border-t pt-4">
                        <div class="text-lg font-bold">Total: $<span id="totalAmount">{{ number_format($purchaseOrder->total_amount, 2) }}</span></div>
                        <div class="space-x-3">
                            <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
function calcTotal() { let total=0; document.querySelectorAll('.item-row').forEach(row=>{const qty=parseFloat(row.querySelector('.qty').value)||0;const cost=parseFloat(row.querySelector('.cost').value)||0;const sub=qty*cost;row.querySelector('.subtotal').textContent='$'+sub.toFixed(2);total+=sub;}); document.getElementById('totalAmount').textContent=total.toFixed(2);}
document.querySelectorAll('.qty,.cost').forEach(el=>el.addEventListener('input',calcTotal));
calcTotal();
</script>
