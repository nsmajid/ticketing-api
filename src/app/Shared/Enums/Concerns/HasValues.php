<?php

namespace App\Shared\Enums\Concerns;

trait HasValues
{
    public static function values(): array
    {
        return array_column(
            self::cases(),
            'value'
        );
    }

    public static function names(): array
    {
        return array_column(
            self::cases(),
            'name'
        );
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->map(fn ($case) => [
                'label' => $case->name,
                'value' => $case->value,
            ])
            ->values()
            ->all();
    }
}