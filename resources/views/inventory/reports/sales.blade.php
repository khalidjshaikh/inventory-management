<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Sales Report') }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Total Sales</div>
                    <div class="text-2xl font-bold text-gray-900">${{ number_format($totalSales, 2) }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Total Orders</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-4">
                    <form method="GET" class="flex gap-4 items-end">
                        <div><label class="block text-xs font-medium text-gray-700">From</label><input type="date" name="date_from" value="{{ request('date_from') }}" class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></div>
                        <div><label class="block text-xs font-medium text-gray-700">To</label><input type="date" name="date_to" value="{{ request('date_to') }}" class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></div>
                        <div><button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">Filter</button></div>
                    </form>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead><tr><th class="text-left text-xs font-medium text-gray-500 uppercase">Sale #</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Date</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Total</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($sales as $sale)
                        <tr><td class="py-2"><a href="{{ route('sales.show', $sale) }}" class="text-blue-600 hover:underline">{{ $sale->sale_number }}</a></td><td class="py-2 text-sm">{{ $sale->sale_date->format('Y-m-d') }}</td><td class="py-2 text-sm">${{ number_format($sale->total_amount, 2) }}</td></tr>
                        @empty
                        <tr><td colspan="3" class="py-4 text-center text-gray-500">No sales found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $sales->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
