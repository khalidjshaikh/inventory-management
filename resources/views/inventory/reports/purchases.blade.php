<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Purchase Orders Report') }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Total Purchases</div>
                    <div class="text-2xl font-bold text-gray-900">${{ number_format($totalPurchases, 2) }}</div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-4">
                    <form method="GET" class="flex flex-wrap gap-4 items-end">
                        <div><label class="block text-xs font-medium text-gray-700">From</label><input type="date" name="date_from" value="{{ request('date_from') }}" class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></div>
                        <div><label class="block text-xs font-medium text-gray-700">To</label><input type="date" name="date_to" value="{{ request('date_to') }}" class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></div>
                        <div><label class="block text-xs font-medium text-gray-700">Status</label><select name="status" class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"><option value="">All</option><option value="pending" {{ request('status')=='pending' ? 'selected':'' }}>Pending</option><option value="partial" {{ request('status')=='partial' ? 'selected':'' }}>Partial</option><option value="received" {{ request('status')=='received' ? 'selected':'' }}>Received</option><option value="cancelled" {{ request('status')=='cancelled' ? 'selected':'' }}>Cancelled</option></select></div>
                        <div><button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">Filter</button></div>
                    </form>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead><tr><th class="text-left text-xs font-medium text-gray-500 uppercase">PO #</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Supplier</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Date</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Status</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Total</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($purchaseOrders as $po)
                        <tr><td class="py-2"><a href="{{ route('purchase-orders.show', $po) }}" class="text-blue-600 hover:underline">{{ $po->order_number }}</a></td><td class="py-2 text-sm">{{ $po->supplier->name }}</td><td class="py-2 text-sm">{{ $po->order_date->format('Y-m-d') }}</td><td class="py-2"><span class="px-2 py-1 text-xs rounded {{ $po->status === 'received' ? 'bg-green-100 text-green-800' : ($po->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($po->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">{{ ucfirst($po->status) }}</span></td><td class="py-2 text-sm">${{ number_format($po->total_amount, 2) }}</td></tr>
                        @empty
                        <tr><td colspan="5" class="py-4 text-center text-gray-500">No purchase orders found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $purchaseOrders->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
