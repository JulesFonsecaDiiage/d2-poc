<?php

namespace App\Enum;
enum EntityManagers: string
{
    case default = 'Default (Base de données principale)';
    case facturation = 'Facturation (Base de données de facturation)';

    public static function values(): array
    {
        return array_column(array_map(fn ($case) => ['name' => $case->name, 'value' => $case->value], self::cases()), 'value', 'name');
    }
}
