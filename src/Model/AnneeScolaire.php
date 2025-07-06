<?php
declare(strict_types=1);

namespace App\Model;

use App\Config\Model;

class AnneeScolaire extends Model
{
    private ?int $idAnneeScolaire;
    private int $debut;
    private int $fin;
    private \DateTime $dateDefinition;

    public function __construct(int|null $idAnneeScolaire, int $debut, ?\DateTime $dateDefinition = null)
    {
        $this->idAnneeScolaire = $idAnneeScolaire;
        $this->debut = $debut;
        $this->fin = $debut + 1;
        $this->dateDefinition = $dateDefinition ?? new \DateTime();
    }

    public function getIdAnneeScolaire(): ?int
    {
        return $this->idAnneeScolaire;
    }

    public function getDebut(): int
    {
        return $this->debut;
    }

    public function getFin(): int
    {
        return $this->fin;
    }

    public function getDateDefinition(): \DateTime
    {
        return $this->dateDefinition;
    }

    public function format(string $pattern = '{debut}-{fin}'): string
    {
        $replace = [
            '{id}' => $this->idAnneeScolaire,
            '{debut}' => $this->debut,
            '{fin}' => $this->fin,
            '{code}' => sprintf('%02d%02d', $this->debut % 100, $this->fin % 100),
            '{date}' => $this->dateDefinition->format('d/m/Y'),
            '{debut2}' => sprintf('%02d', $this->debut % 100),
            '{fin2}' => sprintf('%02d', $this->fin % 100),
        ];
        return strtr($pattern, $replace);
    }


    public function toArray(): array
    {
        return [
            'debut' => $this->debut,
            'fin' => $this->fin,
            'date_definition' => $this->dateDefinition->format('Y-m-d')
        ];
    }

    public static function fromArray(array $data): AnneeScolaire
    {
        return new AnneeScolaire(
            (int) $data['id_annee_scolaire'],
            (int) $data['debut'],
            isset($data['date_definition']) ? new \DateTime($data['date_definition']) : null
        );
    }

    public function toArrayView(): array
    {
        return [
            'id_annee_scolaire' => $this->idAnneeScolaire,
            'debut' => $this->debut,
            'fin' => $this->fin,
            'date_definition' => $this->dateDefinition->format('d/m/Y'),
        ];
    }
}
