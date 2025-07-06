<?php
declare(strict_types=1);

namespace App\Model;

use App\Config\Model;
use App\EnumDomain\GradeProfesseur;

class Professeur extends Model
{
    private ?int $idProfesseur;
    private string $nom;
    private string $prenom;
    private GradeProfesseur $grade;

    public function __construct(?int $idProfesseur, string $nom, string $prenom, GradeProfesseur $grade)
    {
        $this->idProfesseur = $idProfesseur;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->grade = $grade;
    }

    public function getIdProfesseur(): ?int
    {
        return $this->idProfesseur;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function getGrade(): GradeProfesseur
    {
        return $this->grade;
    }

    public function setGrade(GradeProfesseur $grade): void
    {
        $this->grade = $grade;
    }

    public function toArray(): array
    {
        return [
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'grade' => $this->grade->name
        ];
    }

    public static function fromArray(array $data): Professeur
    {
        return new Professeur(
            (int)$data['id_professeur'],
            $data['nom'],
            $data['prenom'],
            GradeProfesseur::fromName($data['grade'])
        );
    }

    public function toArrayView(): array
    {
        return [
            'id_professeur' => $this->idProfesseur,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'grade' => $this->grade->value,
        ];
    }

    public function format(string $pattern = '{grade} {prenom} {nom}'): string
    {
        $replace = [
            '{id}' => $this->idProfesseur,
            '{nom}' => $this->nom,
            '{prenom}' => $this->prenom,
            '{grade}' => $this->grade->value,
        ];

        return strtr($pattern, $replace);
    }
}
