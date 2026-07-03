<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\PurchaseOrderItem;
use App\Models\SaleItem;
use App\Models\StockHistory;

class StockObserver
{
    public function saved(PurchaseOrderItem $item): void
    {
        $product = $item->product;
        $originalQuantity = $product->stock_quantity;

        // Calculate the new stock based on items received
        $totalReceived = $item->where('product_id', $item->product_id)
            ->whereHas('purchaseOrder', fn($q) => $q->whereIn('status', ['received', 'partial']))
            ->sum('quantity_received');

        $product->stock_quantity = $totalReceived;
        $product->saveQuietly();

        // Log stock history for the received quantity
        if ($item->quantity_received > 0) {
            StockHistory::create([
                'product_id' => $item->product_id,
                'type' => 'purchase',
                'quantity_change' => $item->quantity_received,
                'reference_type' => PurchaseOrderItem::class,
                'reference_id' => $item->id,
                'notes' => "Received via PO: {$item->purchaseOrder->order_number}",
            ]);
        }
    }

    public function created(SaleItem $item): void
    {
        $product = $item->product;
        $product->decrement('stock_quantity', $item->quantity);

        StockHistory::create([
            'product_id' => $item->product_id,
            'type' => 'sale',
            'quantity_change' => -$item->quantity,
            'reference_type' => SaleItem::class,
            'reference_id' => $item->id,
            'notes' => "Sold via Sale: {$item->sale->sale_number}",
        ]);
    }
}
