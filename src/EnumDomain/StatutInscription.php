<?php
declare(strict_types=1);

namespace App\EnumDomain;

use App\Config\Enum;

enum StatutInscription: string
{
    use Enum;

    case VALIDEE = 'Validée';
    case SUSPENDUE = 'Suspendu';
    case ANNULEE = 'Annulée';

    public static function style(string $statut): string
    {
        return match ($statut){
            self::VALIDEE->value => 'green',
            self::SUSPENDUE->value => 'orange',
            self::ANNULEE->value => 'red',
            default => 'gray',
        };
    }

    public static function icon(string $type): string
    {
        return match ($type) {
            self::VALIDEE->value => 'fa-check',
            self::SUSPENDUE->value => 'fa-hourglass-half',
            self::ANNULEE->value => 'fa-xmark',
            default => 'fa-solid fa-circle-question',
        };
    }
}
