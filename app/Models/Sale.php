<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['sale_number', 'cashier_id', 'total_amount', 'payment_method', 'notes'])]
class Sale extends Model
{
    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'payment_method' => PaymentMethod::class,
            'created_at' => 'datetime',
        ];
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}