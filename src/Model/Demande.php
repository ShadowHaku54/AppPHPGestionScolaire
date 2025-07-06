<?php
declare(strict_types=1);

namespace App\Model;

use App\Config\Model;
use App\EnumDomain\StatutDemande;
use App\EnumDomain\TypeDemande;
use DateTime;

class Demande extends Model
{
    private ?int $idDemande;
    private ?DateTime $dateDemande;
    private ?int $idAnneeScolaire;
    private string $matEtudiant;
    private int $idInscription;
    private TypeDemande $type;
    private string $motif;
    private ?StatutDemande $statut;

    public function __construct(int|null $idDemande, DateTime|null $dateDemande, int|null $idAnneeScolaire, string $matEtudiant,
                                int  $idInscription, TypeDemande $type, string $motif, StatutDemande|null $statut)
    {
        $this->idDemande = $idDemande;
        $this->dateDemande = $dateDemande ?? new DateTime();
        $this->idAnneeScolaire = $idAnneeScolaire;
        $this->matEtudiant = $matEtudiant;
        $this->idInscription = $idInscription;
        $this->type = $type;
        $this->motif = $motif;
        $this->statut = $statut ?? StatutDemande::EN_ATTENTE;
    }

    public function getIdDemande(): ?int
    {
        return $this->idDemande;
    }

    public function getDateDemande(): DateTime
    {
        return $this->dateDemande;
    }

    public function getIdAnneeScolaire(): int
    {
        return $this->idAnneeScolaire;
    }

    public function getMatEtudiant(): string
    {
        return $this->matEtudiant;
    }

    public function getIdInscription(): int
    {
        return $this->idInscription;
    }

    public function getType(): TypeDemande
    {
        return $this->type;
    }

    public function setType(TypeDemande $type): void
    {
        $this->type = $type;
    }

    public function getMotif(): string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): void
    {
        $this->motif = $motif;
    }

    public function getStatut(): StatutDemande
    {
        return $this->statut;
    }

    public function setStatut(StatutDemande $statut): void
    {
        $this->statut = $statut;
    }

    public function toArray(): array
    {
        return [
            'date_demande' => $this->dateDemande->format('Y-m-d'),
            'id_annee_scolaire' => $this->idAnneeScolaire,
            'mat_etudiant' => $this->matEtudiant,
            'id_inscription' => $this->idInscription,
            'type' => $this->type->name,
            'motif' => $this->motif,
            'statut' => $this->statut->name
        ];
    }

    public static function fromArray(array $data): Demande
    {
        return new Demande(
            (int)$data['id_demande'],
            new DateTime($data['date_demande']),
            (int)$data['id_annee_scolaire'],
            $data['mat_etudiant'],
            (int)$data['id_inscription'],
            TypeDemande::fromName($data['type']),
            $data['motif'],
            StatutDemande::fromName($data['statut'])
        );
    }

    public function toArrayView(): array
    {
        return [
            'id_demande' => $this->idDemande,
            'date_demande' => $this->dateDemande->format('d/m/Y'),
            'id_annee_scolaire' => $this->idAnneeScolaire,
            'mat_etudiant' => $this->matEtudiant,
            'id_inscription' => $this->idInscription,
            'type' => $this->type->value,
            'motif' => $this->motif,
            'statut' => $this->statut->value
        ];
    }

    public function format(string $pattern = '{type} {matricule}'): string
    {
        $replace = [
            '{id}' => $this->idDemande,
            '{date}' => $this->dateDemande->format('d/m/Y'),
            '{annee}' => $this->idAnneeScolaire,
            '{matricule}' => $this->matEtudiant,
            '{inscription}' => $this->idInscription,
            '{type}' => $this->type->value,
            '{motif}' => $this->motif,
            '{statut}' => $this->statut->value,
        ];

        return strtr($pattern, $replace);
    }

}
