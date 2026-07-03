<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function stock()
    {
        $products = Product::with(['category', 'supplier'])
            ->withCount('stockHistories')
            ->orderBy('name')
            ->paginate(20);

        $totalStockValue = Product::sum(\DB::raw('stock_quantity * cost_price'));
        $totalSellingValue = Product::sum(\DB::raw('stock_quantity * selling_price'));

        return view('inventory.reports.stock', compact('products', 'totalStockValue', 'totalSellingValue'));
    }

    public function sales(Request $request)
    {
        $query = Sale::withCount('items');

        if ($request->filled('date_from')) {
            $query->whereDate('sale_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('sale_date', '<=', $request->date_to);
        }

        $sales = $query->latest()->paginate(20);
        $totalSales = $query->sum('total_amount');
        $totalOrders = $query->count();

        return view('inventory.reports.sales', compact('sales', 'totalSales', 'totalOrders'));
    }

    public function purchases(Request $request)
    {
        $query = PurchaseOrder::with('supplier');

        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $purchaseOrders = $query->latest()->paginate(20);
        $totalPurchases = $query->sum('total_amount');

        return view('inventory.reports.purchases', compact('purchaseOrders', 'totalPurchases'));
    }
}
