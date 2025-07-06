<?php
declare(strict_types=1);

namespace App\EnumDomain;

use App\Config\Enum;

enum StatutDemande: string
{
    use Enum;

    case EN_ATTENTE = 'En attente';
    case ACCEPTEE = 'Acceptée';
    case REFUSE = 'Refusée';

    public static function style(string $statut): string
    {
        return match ($statut){
            self::EN_ATTENTE->value => 'yellow',
            self::ACCEPTEE->value => 'green',
            self::REFUSE->value => 'red',
            default => 'gray',
        };
    }
}
