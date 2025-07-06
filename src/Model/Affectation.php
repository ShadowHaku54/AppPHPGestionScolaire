<?php
declare(strict_types=1);

namespace App\Model;

use App\Config\Model;

class Affectation extends Model
{
    private ?int $idAffectation;
    private int $idProfesseur;
    private int $idClasse;

    public function __construct(?int $idAffectation, int $idProfesseur, int $idClasse)
    {
        $this->idAffectation = $idAffectation;
        $this->idProfesseur = $idProfesseur;
        $this->idClasse = $idClasse;
    }

    public function getIdAffectation(): ?int
    {
        return $this->idAffectation;
    }

    public function getIdProfesseur(): int
    {
        return $this->idProfesseur;
    }

    public function getIdClasse(): int
    {
        return $this->idClasse;
    }

    public function toArray(): array
    {
        return [
            'id_professeur' => $this->idProfesseur,
            'id_classe' => $this->idClasse
        ];
    }

    public static function fromArray(array $data): Affectation
    {
        return new Affectation(
            (int) $data['id_affectation'],
            (int) $data['id_professeur'],
            (int) $data['id_classe']
        );
    }

    public function toArrayView(): array
    {
        return [
            'id_affectation' => $this->idAffectation,
            'id_professeur' => $this->idProfesseur,
            'id_classe' => $this->idClasse
        ];
    }

    public function format(string $pattern = '{professeur} – {classe}'): string
    {
        $replace = [
            '{id}'         => $this->idAffectation,
            '{professeur}' => $this->idProfesseur,
            '{classe}'     => $this->idClasse,
        ];

        return strtr($pattern, $replace);
    }
}
