<?php
declare(strict_types=1);

namespace App\EnumDomain;

use App\Config\Enum;

enum TypeInscription: string
{
    use Enum;

    case INSCRIPTION = 'Nouveau';
    case REINSCRIPTION = 'Ancien';

    public static function style(string $statut): string
    {
        return match ($statut){
            self::INSCRIPTION->value => 'violet',
            self::REINSCRIPTION->value => 'gray',
            default => 'gray',
        };
    }
}
