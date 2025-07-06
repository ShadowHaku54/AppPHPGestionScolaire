<?php

namespace App\Config;

use UnitEnum;

/**
 * Trait à utiliser uniquement dans une enum
 *
 * @mixin UnitEnum
 */
trait Enum
{
    public static function fromName(string $nom): ?self {
        foreach (self::cases() as $case) {
            if ($case->name === $nom) {
                return $case;
            }
        }
        return null;
    }
}
