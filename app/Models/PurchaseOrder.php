<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'supplier_id',
        'order_date',
        'received_date',
        'status',
        'total_amount',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'received_date' => 'date',
            'total_amount' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PurchaseOrder $purchaseOrder) {
            if (empty($purchaseOrder->order_number)) {
                $date = now()->format('Ymd');
                $last = static::whereDate('created_at', today())->count();
                $purchaseOrder->order_number = 'PO-' . $date . '-' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
