<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Sale;
use App\Models\StockHistory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $lowStockProducts = Product::where('is_active', true)
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->orderBy('stock_quantity')
            ->get();

        $stats = [
            'total_products' => Product::where('is_active', true)->count(),
            'low_stock_count' => $lowStockProducts->count(),
            'recent_purchase_orders' => PurchaseOrder::with('supplier')->latest()->take(5)->get(),
            'recent_sales' => Sale::latest()->take(5)->get(),
            'recent_stock_movements' => StockHistory::with('product')->latest()->take(10)->get(),
            'total_sales_today' => Sale::whereDate('created_at', today())->sum('total_amount'),
        ];

        return view('inventory.dashboard', compact('lowStockProducts', 'stats'));
    }
}
