<?php
declare(strict_types=1);

namespace App\Model;

use App\Config\Model;

class Dispense extends Model
{
    private ?int $idDispense;
    private int $idProfesseur;
    private int $idModule;

    public function __construct(?int $idDispense, int $idProfesseur, int $idModule)
    {
        $this->idDispense = $idDispense;
        $this->idProfesseur = $idProfesseur;
        $this->idModule = $idModule;
    }

    public function getIdDispense(): ?int
    {
        return $this->idDispense;
    }

    public function getIdProfesseur(): int
    {
        return $this->idProfesseur;
    }

    public function getIdModule(): int
    {
        return $this->idModule;
    }

    public function toArray(): array
    {
        return [
            'id_professeur' => $this->idProfesseur,
            'id_module' => $this->idModule
        ];
    }

    public static function fromArray(array $data): Dispense
    {
        return new Dispense(
            (int) $data['id_dispense'],
            (int) $data['id_professeur'],
            (int) $data['id_module']
        );
    }

    public function toArrayView(): array
    {
        return [
            'id_dispense' => $this->idDispense,
            'id_professeur' => $this->idProfesseur,
            'id_module' => $this->idModule
        ];
    }

    public function format(string $pattern = '{professeur} {module}'): string
    {
        $replace = [
            '{id}'         => $this->idDispense,
            '{professeur}' => $this->idProfesseur,
            '{module}'     => $this->idModule,
        ];

        return strtr($pattern, $replace);
    }
}
