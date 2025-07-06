<?php
declare(strict_types=1);

namespace App\Model;

use App\Config\Model;
use App\EnumDomain\Filiere;
use App\EnumDomain\Niveau;
use DateTime;

class Classe extends Model
{
    private ?int $idClasse;
    private string $libelle;
    private Niveau $niveau;
    private Filiere $filiere;
    private DateTime $debutInscription;
    private DateTime $finInscription;
    private ?int $idAnneeScolaire;

    public function __construct(
        ?int      $idClasse,
        string    $libelle,
        Niveau    $niveau,
        Filiere   $filiere,
        DateTime $debutInscription,
        DateTime $finInscription,
        ?int       $idAnneeScolaire
    )
    {
        $this->idClasse = $idClasse;
        $this->libelle = $libelle;
        $this->niveau = $niveau;
        $this->filiere = $filiere;
        $this->debutInscription = $debutInscription;
        $this->finInscription = $finInscription;
        $this->idAnneeScolaire = $idAnneeScolaire;
    }

    public function getIdClasse(): ?int
    {
        return $this->idClasse;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public function getNiveau(): Niveau
    {
        return $this->niveau;
    }

    public function setNiveau(Niveau $niveau): void
    {
        $this->niveau = $niveau;
    }

    public function getFiliere(): Filiere
    {
        return $this->filiere;
    }

    public function setFiliere(Filiere $filiere): void
    {
        $this->filiere = $filiere;
    }

    public function getDebutInscription(): DateTime
    {
        return $this->debutInscription;
    }

    public function setDebutInscription(DateTime $date): void
    {
        $this->debutInscription = $date;
    }

    public function getFinInscription(): DateTime
    {
        return $this->finInscription;
    }

    public function setFinInscription(DateTime $date): void
    {
        $this->finInscription = $date;
    }

    public function getIdAnneeScolaire(): int
    {
        return $this->idAnneeScolaire;
    }

    public function toArray(): array
    {
        return [
            'libelle' => $this->libelle,
            'niveau' => $this->niveau->name,
            'filiere' => $this->filiere->name,
            'debut_inscription' => $this->debutInscription->format('Y-m-d'),
            'fin_inscription' => $this->finInscription->format('Y-m-d'),
            'id_annee_scolaire' => $this->idAnneeScolaire
        ];
    }

    public static function fromArray(array $data): Classe
    {
        return new Classe(
            (int)$data['id_classe'],
            $data['libelle'],
            Niveau::fromName($data['niveau']),
            Filiere::fromName($data['filiere']),
            new DateTime($data['debut_inscription']),
            new DateTime($data['fin_inscription']),
            (int)$data['id_annee_scolaire']
        );
    }

    public function toArrayView(): array
    {
        return [
            'id_classe' => $this->idClasse,
            'libelle' => $this->libelle,
            'niveau' => $this->niveau->value,
            'filiere' => $this->filiere->value,
            'debut_inscription' => $this->debutInscription->format('d/m/y'),
            'fin_inscription' => $this->finInscription->format('d/m/y'),
            'id_annee_scolaire' => $this->idAnneeScolaire
        ];
    }

    public function format(string $pattern = '{niveau} {libelle}'): string
    {
        $replace = [
            '{id}'      => $this->idClasse,
            '{libelle}' => $this->libelle,
            '{niveau}'  => $this->niveau->value,
            '{filiere}' => $this->filiere->value,
            '{debut}'   => $this->debutInscription->format('d/m/Y'),
            '{fin}'     => $this->finInscription->format('d/m/Y'),
            '{annee}'   => $this->idAnneeScolaire,
        ];

        return strtr($pattern, $replace);
    }
}
