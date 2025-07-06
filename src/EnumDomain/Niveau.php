<?php
declare(strict_types=1);

namespace App\EnumDomain;

use App\Config\Enum;

enum Niveau: string
{
    use Enum;

    case L1 = 'L1';
    case L2 = 'L2';
    case L3 = 'L3';
    case M1 = 'M1';
    case M2 = 'M2';
}
