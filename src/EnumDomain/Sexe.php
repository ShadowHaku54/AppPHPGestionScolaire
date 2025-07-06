<?php
declare(strict_types=1);

namespace App\EnumDomain;

use App\Config\Enum;

enum Sexe: string
{
    use Enum;

    case M = 'Masculin';
    case F = 'Féminin';

    public static function style(mixed $sexe): string
    {
        return match ($sexe){
            self::M->value => 'blue',
            self::F->value => 'pink',
            default => 'gray',
        };
    }

    public static function icon(string $sexe): string
    {
        return match ($sexe) {
            self::M->value => 'fa-solid fa-mars',
            self::F->value => 'fa-solid fa-venus',
            default => 'fa-solid fa-circle-question',
        };
    }
}
