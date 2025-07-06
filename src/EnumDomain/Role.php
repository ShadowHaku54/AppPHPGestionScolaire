<?php
declare(strict_types=1);

namespace App\EnumDomain;

use App\Config\Enum;

enum Role: string
{
    use Enum;

    case RP = 'RP';
    case AC = 'AC';
    case ETU = 'ETU';
}
