<?php

namespace App\Enums;

use App\Enums\Traits\HasOptions;

enum UserRole: string
{
    use HasOptions;

    case Admin = 'admin';
    case Cashier = 'cashier';

    public function label(): string
    {
        return match($this) {
            self::Admin => 'Administrator',
            self::Cashier => 'Cashier',
        };
    }
}