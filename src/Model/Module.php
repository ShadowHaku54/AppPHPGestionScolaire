<?php
declare(strict_types=1);

namespace App\Model;

use App\Config\Model;

class Module extends Model
{
    private ?int $idModule;
    private string $nom;
    private int $nbHeure;

    public function __construct(?int $idModule, string $nom, int $nbHeure)
    {
        $this->idModule = $idModule;
        $this->nom = $nom;
        $this->nbHeure = $nbHeure;
    }

    public function getIdModule(): ?int
    {
        return $this->idModule;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getNbHeure(): int
    {
        return $this->nbHeure;
    }

    public function setNbHeure(int $nbHeure): void
    {
        $this->nbHeure = $nbHeure;
    }

    public function toArray(): array
    {
        return [
            'nom' => $this->nom,
            'nb_heure' => $this->nbHeure
        ];
    }

    public static function fromArray(array $data): Module
    {
        return new Module(
            (int)$data['id_module'],
            $data['nom'],
            (int)$data['nb_heure']
        );
    }

    public function toArrayView(): array
    {
        return [
            'id_module' => $this->idModule,
            'nom' => $this->nom,
            'nb_heure' => $this->nbHeure
        ];
    }

    public function format(string $pattern = '{nom}'): string
    {
        $replace = [
            '{id}' => $this->idModule,
            '{nom}' => $this->nom,
            '{heures}' => $this->nbHeure,
        ];

        return strtr($pattern, $replace);
    }
}
