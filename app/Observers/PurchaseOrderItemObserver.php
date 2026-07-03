<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\PurchaseOrderItem;
use App\Models\StockHistory;

class PurchaseOrderItemObserver
{
    public function saved(PurchaseOrderItem $item): void
    {
        if ($item->quantity_received > 0) {
            // Calculate total received for this product across all POs
            $totalReceived = PurchaseOrderItem::where('product_id', $item->product_id)
                ->whereHas('purchaseOrder', fn($q) => $q->whereIn('status', ['received', 'partial']))
                ->sum('quantity_received');

            Product::withoutTimestamps(fn() => $item->product->update(['stock_quantity' => $totalReceived]));

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
}
