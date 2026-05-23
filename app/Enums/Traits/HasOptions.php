<?php

namespace App\Enums\Traits;

use BackedEnum;

trait HasOptions
{
    /**
     * Returns an associative array formatted perfectly for Blade <select> options.
     * e.g., ['tablet' => 'Tablet', 'capsule' => 'Capsule']
     */
    public static function options(): array
    {
        // 1. Ensure the class using this trait is actually a BackedEnum
        if (!is_subclass_of(static::class, BackedEnum::class)) {
            return [];
        }

        return array_column(
            array_map(fn(BackedEnum $enum) => [
                'value' => $enum->value,
                'label' => method_exists($enum, 'label') ? $enum->label() : ucfirst($enum->name),
            ], static::cases()),
            'label',
            'value'
        );
    }

    /**
     * Filters options based on static methods like inReasons() or outReasons()
     */
    public static function optionsForGroup(string $group): array
    {
        $methodName = $group . 'Reasons';

        if (!method_exists(static::class, $methodName)) {
            return static::options();
        }

        $filteredCases = static::$methodName();

        return array_column(
            array_map(fn(BackedEnum $enum) => [
                'value' => $enum->value,
                'label' => method_exists($enum, 'label') ? $enum->label() : ucfirst($enum->name),
            ], $filteredCases),
            'label',
            'value'
        );
    }
}