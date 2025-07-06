<?php
declare(strict_types=1);

namespace App\EnumDomain;

use App\Config\Enum;

enum TypeDemande: string
{
    use Enum;

    case ANNULATION = 'Annulation';
    case SUSPENSION = 'Suspension';


    public static function style(string $type): string
    {
        return match ($type) {
            self::ANNULATION->value => 'red',
            self::SUSPENSION->value => 'orange',
            default => 'gray',
        };
    }

    public static function icon(string $type): string
    {
        return match ($type) {
            self::ANNULATION->value => 'fa-solid fa-ban',
            self::SUSPENSION->value => 'fa-solid fa-pause',
            default => 'fa-solid fa-circle-question',
        };
    }

}
