<?php

namespace App\Enums;

use App\Enums\Traits\HasOptions;

enum StockMovementReason: string
{
    use HasOptions;

    // IN
    case Purchase = 'purchase';
    case ReturnCustomer = 'return_customer';
    case AdjustmentIn = 'adjustment_in';

    // OUT
    case Sale = 'sale';
    case Expired = 'expired';
    case Damaged = 'damaged';
    case ReturnSupplier = 'return_supplier';
    case AdjustmentOut = 'adjustment_out';

    public function label(): string
    {
        return match($this) {
            self::Purchase => 'Purchase',
            self::ReturnCustomer => 'Return from Customer',
            self::AdjustmentIn => 'Adjustment (In)',
            self::Sale => 'Sale',
            self::Expired => 'Expired',
            self::Damaged => 'Damaged',
            self::ReturnSupplier => 'Return to Supplier',
            self::AdjustmentOut => 'Adjustment (Out)',
        };
    }

    public static function inReasons(): array
    {
        return [
            self::Purchase,
            self::ReturnCustomer,
            self::AdjustmentIn,
        ];
    }

    public static function outReasons(): array
    {
        return [
            self::Sale,
            self::Expired,
            self::Damaged,
            self::ReturnSupplier,
            self::AdjustmentOut,
        ];
    }
}