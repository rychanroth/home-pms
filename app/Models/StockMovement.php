<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\StockMovementReason;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'product_id',
    'reason',
    'supplier_id',
    'sale_id',
    'quantity',
    'unit_cost',
    'reference',
    'notes',
    'created_by_id',
])]
class StockMovement extends Model
{
    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'reason' => StockMovementReason::class,
            'created_at' => 'datetime',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
