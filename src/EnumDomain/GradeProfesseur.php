<?php
declare(strict_types=1);

namespace App\EnumDomain;

use App\Config\Enum;

enum GradeProfesseur: string
{
    use Enum;

    case ASSISTANT = 'Assistant';
    case MAITRE_CONFERENCE = 'Maitre de conférence';
    case PROF_TITULAIRE = 'Prof titulaire';
    case PROF_TITULAIRE_PLUS = 'Prof titulaire +';
    case PROF_EMERITE = 'Prof emerite';
    case STATUT_TEMPORAIRE = 'Statut temporaire';
    case AUTRE = 'Autre';

    public static function style(mixed $grade): string
    {
        return match ($grade) {
            self::ASSISTANT->value => 'blue',
            self::MAITRE_CONFERENCE->value => 'green',
            self::PROF_TITULAIRE->value => 'orange',
            self::PROF_TITULAIRE_PLUS->value => 'purple',
            self::PROF_EMERITE->value => 'red',
            self::STATUT_TEMPORAIRE->value => 'gray',
            self::AUTRE->value => 'black',
            default => 'gray',
        };
    }
}
