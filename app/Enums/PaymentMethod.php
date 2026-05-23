<?php

namespace App\Enums;

use App\Enums\Traits\HasOptions;

enum PaymentMethod: string
{
    use HasOptions;

    case Cash = 'cash';
    case Card = 'card';
    case Insurance = 'insurance';

    public function label(): string
    {
        return match($this) {
            self::Cash => 'Cash',
            self::Card => 'Card',
            self::Insurance => 'Insurance',
        };
    }
}