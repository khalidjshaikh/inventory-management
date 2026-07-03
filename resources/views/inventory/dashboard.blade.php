<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Total Products</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['total_products'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Low Stock Items</div>
                    <div class="text-3xl font-bold {{ $stats['low_stock_count'] > 0 ? 'text-red-600' : 'text-gray-900' }}">
                        {{ $stats['low_stock_count'] }}
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Sales Today</div>
                    <div class="text-3xl font-bold text-green-600">${{ number_format($stats['total_sales_today'], 2) }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Active Products</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['total_products'] }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Low Stock Alerts -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Low Stock Alerts</h3>
                    </div>
                    <div class="p-6">
                        @if($lowStockProducts->isEmpty())
                            <p class="text-gray-500">All products are well-stocked.</p>
                        @else
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                        <th class="text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                                        <th class="text-left text-xs font-medium text-gray-500 uppercase">Threshold</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($lowStockProducts as $product)
                                        <tr>
                                            <td class="py-2">
                                                <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:underline">{{ $product->name }}</a>
                                            </td>
                                            <td class="py-2 text-red-600 font-bold">{{ $product->stock_quantity }}</td>
                                            <td class="py-2">{{ $product->low_stock_threshold }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

                <!-- Recent Stock Movements -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Recent Stock Movements</h3>
                    </div>
                    <div class="p-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase">Change</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($stats['recent_stock_movements'] as $movement)
                                    <tr>
                                        <td class="py-2 text-sm">{{ $movement->product->name ?? 'Deleted' }}</td>
                                        <td class="py-2">
                                            <span class="px-2 py-1 text-xs rounded {{ $movement->type === 'purchase' ? 'bg-green-100 text-green-800' : ($movement->type === 'sale' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($movement->type) }}
                                            </span>
                                        </td>
                                        <td class="py-2 text-sm {{ $movement->quantity_change > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Purchase Orders -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Recent Purchase Orders</h3>
                    </div>
                    <div class="p-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($stats['recent_purchase_orders'] as $po)
                                    <tr>
                                        <td class="py-2 text-sm">
                                            <a href="{{ route('purchase-orders.show', $po) }}" class="text-blue-600 hover:underline">{{ $po->order_number }}</a>
                                        </td>
                                        <td class="py-2 text-sm">{{ $po->supplier->name }}</td>
                                        <td class="py-2">
                                            <span class="px-2 py-1 text-xs rounded {{ $po->status === 'received' ? 'bg-green-100 text-green-800' : ($po->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($po->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                                                {{ ucfirst($po->status) }}
                                            </span>
                                        </td>
                                        <td class="py-2 text-sm">${{ number_format($po->total_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Sales -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Recent Sales</h3>
                    </div>
                    <div class="p-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase">Sale #</th>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($stats['recent_sales'] as $sale)
                                    <tr>
                                        <td class="py-2 text-sm">
                                            <a href="{{ route('sales.show', $sale) }}" class="text-blue-600 hover:underline">{{ $sale->sale_number }}</a>
                                        </td>
                                        <td class="py-2 text-sm">{{ $sale->sale_date->format('Y-m-d') }}</td>
                                        <td class="py-2 text-sm">${{ number_format($sale->total_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
