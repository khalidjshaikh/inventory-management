<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreSaleRequest;
use App\Models\Product;
use App\Models\Sale;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::withCount('items')->latest()->paginate(15);
        return view('inventory.sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('inventory.sales.create', compact('products'));
    }

    public function store(StoreSaleRequest $request)
    {
        $data = $request->validated();

        $totalAmount = 0;
        foreach ($data['items'] as $item) {
            $totalAmount += $item['quantity'] * $item['unit_price'];
        }

        $sale = Sale::create([
            'sale_date' => $data['sale_date'],
            'total_amount' => $totalAmount,
            'notes' => $data['notes'] ?? null,
        ]);

        foreach ($data['items'] as $item) {
            $sale->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('sales.show', $sale)
            ->with('success', 'Sale created successfully.');
    }

    public function show(Sale $sale)
    {
        $sale->load('items.product');
        return view('inventory.sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $sale->load('items.product');
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('inventory.sales.edit', compact('sale', 'products'));
    }

    public function update(StoreSaleRequest $request, Sale $sale)
    {
        $data = $request->validated();

        $totalAmount = 0;
        foreach ($data['items'] as $item) {
            $totalAmount += $item['quantity'] * $item['unit_price'];
        }

        $sale->update([
            'sale_date' => $data['sale_date'],
            'total_amount' => $totalAmount,
            'notes' => $data['notes'] ?? null,
        ]);

        $sale->items()->delete();
        foreach ($data['items'] as $item) {
            $sale->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('sales.show', $sale)
            ->with('success', 'Sale updated successfully.');
    }

    public function destroy(Sale $sale)
    {
        $sale->items()->delete();
        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Sale deleted successfully.');
    }
}
