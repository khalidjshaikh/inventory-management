<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_number',
        'sale_date',
        'total_amount',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'sale_date' => 'date',
            'total_amount' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Sale $sale) {
            if (empty($sale->sale_number)) {
                $date = now()->format('Ymd');
                $last = static::whereDate('created_at', today())->count();
                $sale->sale_number = 'SAL-' . $date . '-' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
