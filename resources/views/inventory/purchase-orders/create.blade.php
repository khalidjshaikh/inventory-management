<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Create Purchase Order') }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form id="poForm" action="{{ route('purchase-orders.store') }}" method="POST">@csrf
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div><label class="block text-sm font-medium text-gray-700">Supplier</label><select name="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required><option value="">— Select —</option>@foreach($suppliers as $sup)<option value="{{ $sup->id }}" {{ old('supplier_id')==$sup->id ? 'selected':'' }}>{{ $sup->name }}</option>@endforeach</select>@error('supplier_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700">Order Date</label><input type="date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>@error('order_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                    </div>
                    <div class="mb-4"><label class="block text-sm font-medium text-gray-700">Notes</label><textarea name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea></div>

                    <h4 class="text-md font-medium text-gray-900 mb-3">Items</h4>
                    @error('items')<p class="text-red-500 text-xs mb-2">{{ $message }}</p>@enderror
                    <table class="min-w-full divide-y divide-gray-200 mb-4" id="itemsTable">
                        <thead><tr><th class="text-left text-xs font-medium text-gray-500 uppercase">Product</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Qty</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Unit Cost</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th><th></th></tr></thead>
                        <tbody id="itemsBody">
                            <tr class="item-row">
                                <td class="py-2 pr-2"><select name="items[0][product_id]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required><option value="">— Select —</option>@foreach($products as $p)<option value="{{ $p->id }}">{{ $p->name }} ({{ $p->sku }})</option>@endforeach</select></td>
                                <td class="py-2 pr-2"><input type="number" name="items[0][quantity_ordered]" min="1" value="1" class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm qty" required></td>
                                <td class="py-2 pr-2"><input type="number" step="0.01" min="0" name="items[0][unit_cost]" value="0" class="block w-28 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm cost" required></td>
                                <td class="py-2 text-sm subtotal">$0.00</td>
                                <td class="py-2"><button type="button" class="text-red-500 hover:text-red-700 remove-item">×</button></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" id="addItem" class="mb-6 px-3 py-1 border rounded-md text-sm hover:bg-gray-50">+ Add Item</button>

                    <div class="flex justify-between items-center border-t pt-4">
                        <div class="text-lg font-bold">Total: $<span id="totalAmount">0.00</span></div>
                        <div class="space-x-3">
                            <a href="{{ route('purchase-orders.index') }}" class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Purchase Order</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
let itemIndex = 1;
document.getElementById('addItem').addEventListener('click', function() {
    const tbody = document.getElementById('itemsBody');
    const row = document.createElement('tr');
    row.className = 'item-row';
    row.innerHTML = `
        <td class="py-2 pr-2"><select name="items[${itemIndex}][product_id]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required><option value="">— Select —</option>@foreach($products as $p)<option value="{{ $p->id }}">{{ $p->name }} ({{ $p->sku }})</option>@endforeach</select></td>
        <td class="py-2 pr-2"><input type="number" name="items[${itemIndex}][quantity_ordered]" min="1" value="1" class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm qty" required></td>
        <td class="py-2 pr-2"><input type="number" step="0.01" min="0" name="items[${itemIndex}][unit_cost]" value="0" class="block w-28 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm cost" required></td>
        <td class="py-2 text-sm subtotal">$0.00</td>
        <td class="py-2"><button type="button" class="text-red-500 hover:text-red-700 remove-item">×</button></td>
    `;
    tbody.appendChild(row);
    itemIndex++;
    attachEvents();
});

function attachEvents() {
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.removeEventListener('click', handleRemove);
        btn.addEventListener('click', handleRemove);
    });
    document.querySelectorAll('.qty, .cost').forEach(input => {
        input.removeEventListener('input', calcTotal);
        input.addEventListener('input', calcTotal);
    });
}

function handleRemove(e) {
    if (document.querySelectorAll('.item-row').length > 1) {
        e.target.closest('.item-row').remove();
        calcTotal();
    }
}

function calcTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const cost = parseFloat(row.querySelector('.cost').value) || 0;
        const sub = qty * cost;
        row.querySelector('.subtotal').textContent = '$' + sub.toFixed(2);
        total += sub;
    });
    document.getElementById('totalAmount').textContent = total.toFixed(2);
}

attachEvents();
calcTotal();
</script>
