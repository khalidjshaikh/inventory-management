<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\ReceivePurchaseOrderRequest;
use App\Http\Requests\Inventory\StorePurchaseOrderRequest;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'items'])
            ->withCount('items')
            ->latest()
            ->paginate(15);

        return view('inventory.purchase-orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('inventory.purchase-orders.create', compact('suppliers', 'products'));
    }

    public function store(StorePurchaseOrderRequest $request)
    {
        $data = $request->validated();

        $totalAmount = 0;
        foreach ($data['items'] as $item) {
            $totalAmount += $item['quantity_ordered'] * $item['unit_cost'];
        }

        $purchaseOrder = PurchaseOrder::create([
            'supplier_id' => $data['supplier_id'],
            'order_date' => $data['order_date'],
            'total_amount' => $totalAmount,
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
        ]);

        foreach ($data['items'] as $item) {
            $purchaseOrder->items()->create([
                'product_id' => $item['product_id'],
                'quantity_ordered' => $item['quantity_ordered'],
                'unit_cost' => $item['unit_cost'],
                'subtotal' => $item['quantity_ordered'] * $item['unit_cost'],
            ]);
        }

        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('success', 'Purchase order created successfully.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'items.product']);
        return view('inventory.purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        if (in_array($purchaseOrder->status, ['received', 'cancelled'])) {
            return redirect()->route('purchase-orders.show', $purchaseOrder)
                ->with('error', 'Cannot edit a ' . $purchaseOrder->status . ' order.');
        }

        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $purchaseOrder->load('items.product');
        return view('inventory.purchase-orders.edit', compact('purchaseOrder', 'suppliers', 'products'));
    }

    public function update(StorePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        if (in_array($purchaseOrder->status, ['received', 'cancelled'])) {
            return redirect()->route('purchase-orders.show', $purchaseOrder)
                ->with('error', 'Cannot update a ' . $purchaseOrder->status . ' order.');
        }

        $data = $request->validated();

        $totalAmount = 0;
        foreach ($data['items'] as $item) {
            $totalAmount += $item['quantity_ordered'] * $item['unit_cost'];
        }

        $purchaseOrder->update([
            'supplier_id' => $data['supplier_id'],
            'order_date' => $data['order_date'],
            'total_amount' => $totalAmount,
            'notes' => $data['notes'] ?? null,
        ]);

        $purchaseOrder->items()->delete();
        foreach ($data['items'] as $item) {
            $purchaseOrder->items()->create([
                'product_id' => $item['product_id'],
                'quantity_ordered' => $item['quantity_ordered'],
                'unit_cost' => $item['unit_cost'],
                'subtotal' => $item['quantity_ordered'] * $item['unit_cost'],
            ]);
        }

        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('success', 'Purchase order updated successfully.');
    }

    public function receive(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status === 'cancelled') {
            return back()->with('error', 'Cannot receive a cancelled order.');
        }

        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'exists:purchase_order_items,id'],
            'items.*.quantity_received' => ['required', 'integer', 'min:0'],
        ]);

        $allReceived = true;
        $anyReceived = false;

        foreach ($validated['items'] as $itemData) {
            $item = $purchaseOrder->items()->findOrFail($itemData['id']);
            $item->update([
                'quantity_received' => $itemData['quantity_received'],
            ]);

            if ($itemData['quantity_received'] > 0) {
                $anyReceived = true;
            }
            if ($itemData['quantity_received'] < $item->quantity_ordered) {
                $allReceived = false;
            }
        }

        $purchaseOrder->update([
            'status' => $allReceived ? 'received' : ($anyReceived ? 'partial' : 'pending'),
            'received_date' => $anyReceived ? now() : null,
        ]);

        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('success', 'Purchase order items received successfully.');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status === 'received') {
            return back()->with('error', 'Cannot delete a received order.');
        }
        $purchaseOrder->items()->delete();
        $purchaseOrder->delete();

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase order deleted successfully.');
    }
}
