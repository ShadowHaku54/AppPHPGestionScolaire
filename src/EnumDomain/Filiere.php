<?php
declare(strict_types=1);

namespace App\EnumDomain;

use App\Config\Enum;

enum Filiere: string
{
    use Enum;

    case GLRS = 'GLRS';
    case TTL = 'TTL';
    case IAGE = 'IAGE';
    case MAIE = 'MAIE';
    case ETSE = 'ETSE';

    public static function style(mixed $niveau): string
    {
        return match ($niveau) {
            self::GLRS->value => 'blue',
            self::MAIE->value => 'green',
            self::IAGE->value => 'orange',
            self::TTL->value => 'yellow',
            self::ETSE->value => 'gray',
            default => 'gray',
        };
    }
}
