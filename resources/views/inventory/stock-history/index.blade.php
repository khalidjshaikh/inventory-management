<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Stock History') }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <form method="GET" class="flex flex-wrap gap-4 items-end">
                        <div><label class="block text-xs font-medium text-gray-700">Product ID</label><input type="number" name="product_id" value="{{ request('product_id') }}" class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></div>
                        <div><label class="block text-xs font-medium text-gray-700">Type</label><select name="type" class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"><option value="">All</option><option value="purchase" {{ request('type')=='purchase' ? 'selected':'' }}>Purchase</option><option value="sale" {{ request('type')=='sale' ? 'selected':'' }}>Sale</option><option value="adjustment" {{ request('type')=='adjustment' ? 'selected':'' }}>Adjustment</option><option value="return" {{ request('type')=='return' ? 'selected':'' }}>Return</option></select></div>
                        <div><label class="block text-xs font-medium text-gray-700">From</label><input type="date" name="date_from" value="{{ request('date_from') }}" class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></div>
                        <div><label class="block text-xs font-medium text-gray-700">To</label><input type="date" name="date_to" value="{{ request('date_to') }}" class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></div>
                        <div><button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">Filter</button></div>
                    </form>
                </div>
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Change</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($histories as $history)
                            <tr>
                                <td class="py-2 text-sm">{{ $history->created_at->format('Y-m-d H:i') }}</td>
                                <td class="py-2">
                                    @if($history->product)
                                    <a href="{{ route('products.show', $history->product) }}" class="text-blue-600 hover:underline">{{ $history->product->name }}</a>
                                    @else
                                    <span class="text-gray-400">Deleted</span>
                                    @endif
                                </td>
                                <td class="py-2"><span class="px-2 py-1 text-xs rounded {{ $history->type === 'purchase' ? 'bg-green-100 text-green-800' : ($history->type === 'sale' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">{{ ucfirst($history->type) }}</span></td>
                                <td class="py-2 text-sm {{ $history->quantity_change > 0 ? 'text-green-600 font-bold' : 'text-red-600 font-bold' }}">{{ $history->quantity_change > 0 ? '+' : '' }}{{ $history->quantity_change }}</td>
                                <td class="py-2 text-sm text-gray-500">{{ $history->notes ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="py-4 text-center text-gray-500">No stock history found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $histories->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
