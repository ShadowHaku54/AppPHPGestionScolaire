<?php
declare(strict_types=1);

namespace App\Model;

use App\Config\Model;
use App\EnumDomain\StatutInscription;
use App\EnumDomain\TypeInscription;

class Inscription extends Model
{
    private ?int $idInscription;
    private \DateTime $dateInscription;
    private int $idAnneeScolaire;
    private string $matEtudiant;
    private int $idClasse;
    private StatutInscription $statut;
    private TypeInscription $type;

    public function __construct(
        ?int            $idInscription,
        \DateTime       $dateInscription,
        int             $idAnneeScolaire,
        string          $matEtudiant,
        int             $idClasse,
        TypeInscription $type
    )
    {
        $this->idInscription = $idInscription;
        $this->dateInscription = $dateInscription;
        $this->idAnneeScolaire = $idAnneeScolaire;
        $this->matEtudiant = $matEtudiant;
        $this->idClasse = $idClasse;
        $this->type = $type;
        $this->statut = StatutInscription::VALIDEE;
    }

    public function getIdInscription(): ?int
    {
        return $this->idInscription;
    }

    public function getDateInscription(): \DateTime
    {
        return $this->dateInscription;
    }

    public function getIdAnneeScolaire(): int
    {
        return $this->idAnneeScolaire;
    }

    public function getMatEtudiant(): string
    {
        return $this->matEtudiant;
    }

    public function getIdClasse(): int
    {
        return $this->idClasse;
    }

    public function getStatut(): StatutInscription
    {
        return $this->statut;
    }

    public function setStatut(StatutInscription $statut): void
    {
        $this->statut = $statut;
    }

    public function getType(): TypeInscription
    {
        return $this->type;
    }

    public function toArray(): array
    {
        return [
            'date_inscription' => $this->dateInscription->format('Y-m-d'),
            'id_annee_scolaire' => $this->idAnneeScolaire,
            'mat_etudiant' => $this->matEtudiant,
            'id_classe' => $this->idClasse,
            'statut' => $this->statut->name,
            'type' => $this->type->name
        ];
    }

    public static function fromArray(array $data): Inscription
    {
        $instance = new Inscription(
            (int)$data['id_inscription'],
            new \DateTime($data['date_inscription']),
            (int)$data['id_annee_scolaire'],
            $data['mat_etudiant'],
            (int)$data['id_classe'],
            TypeInscription::fromName($data['type'])
        );
        if (isset($data['statut'])) {
            $instance->setStatut(StatutInscription::fromName($data['statut']));
        }
        return $instance;
    }

    public function toArrayView(): array
    {
        return [
            'id_inscription' => $this->idInscription,
            'date_inscription' => $this->dateInscription->format('d/m/Y'),
            'id_annee_scolaire' => $this->idAnneeScolaire,
            'mat_etudiant' => $this->matEtudiant,
            'id_classe' => $this->idClasse,
            'statut' => $this->statut->value,
            'type' => $this->type->value
        ];
    }

    public function format(string $pattern = '{type} {matricule}'): string
    {
        $replace = [
            '{id}' => $this->idInscription,
            '{date}' => $this->dateInscription->format('d/m/Y'),
            '{annee}' => $this->idAnneeScolaire,
            '{matricule}' => $this->matEtudiant,
            '{classe}' => $this->idClasse,
            '{statut}' => $this->statut->value,
            '{type}' => $this->type->value,
        ];

        return strtr($pattern, $replace);
    }
}
