<?php

namespace App\Observers;

use App\Models\SaleItem;
use App\Models\StockHistory;

class SaleItemObserver
{
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
