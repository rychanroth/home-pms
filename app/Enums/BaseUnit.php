<?php

namespace App\Enums;

use App\Enums\Traits\HasOptions;

enum BaseUnit: string
{
    use HasOptions;

    // Medicine - Oral
    case Tablet = 'tablet';
    case Capsule = 'capsule';
    case Syrup = 'syrup';

    // Medicine - Liquid/Measurement
    case Ml = 'ml';
    case G = 'g';
    case Mg = 'mg';

    // Medicine - Packaging
    case Vial = 'vial';
    case Ampoule = 'ampoule';
    case Tube = 'tube';
    case Bottle = 'bottle';

    // Medical Equipment
    case Piece = 'piece';
    case Pack = 'pack';
    case Roll = 'roll';
    case Box = 'box';
    case Set = 'set';

    // Baby Care / Skin Care
    case Diaper = 'diaper';
    case Wipes = 'wipes';
    case Sachet = 'sachet';

    // General
    case Unit = 'unit';

    public function label(): string
    {
        return match($this) {
            self::Tablet => 'Tablet',
            self::Capsule => 'Capsule',
            self::Syrup => 'Syrup',
            self::Ml => 'Milliliter (mL)',
            self::G => 'Gram (g)',
            self::Mg => 'Milligram (mg)',
            self::Vial => 'Vial',
            self::Ampoule => 'Ampoule',
            self::Tube => 'Tube',
            self::Bottle => 'Bottle',
            self::Piece => 'Piece',
            self::Pack => 'Pack',
            self::Roll => 'Roll',
            self::Box => 'Box',
            self::Set => 'Set',
            self::Diaper => 'Diaper',
            self::Wipes => 'Wipes',
            self::Sachet => 'Sachet',
            self::Unit => 'Unit',
        };
    }
}