<?php
declare(strict_types=1);

namespace App\Model;


use App\EnumDomain\Role;
use App\EnumDomain\Sexe;

class Etudiant extends Utilisateur
{
    private string $matEtudiant;
    private string $personalEmail;
    private string $adresse;
    private Sexe $sexe;

    public function __construct(string $nom, string $prenom, string $emailOfSchool, string $password,
                                string $matEtudiant, string $personalEmail, string $adresse, Sexe $sexe)
    {
        parent::__construct($nom, $prenom, $emailOfSchool, $password, Role::ETU);
        $this->matEtudiant = $matEtudiant;
        $this->personalEmail = $personalEmail;
        $this->adresse = $adresse;
        $this->sexe = $sexe;
    }

    public function getMatEtudiant(): string
    {
        return $this->matEtudiant;
    }

    public function getPersonalEmail(): string
    {
        return $this->personalEmail;
    }

    public function setPersonalEmail(string $personalEmail): void
    {
        $this->personalEmail = $personalEmail;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function getSexe(): Sexe
    {
        return $this->sexe;
    }

    public function setSexe(Sexe $sexe): void
    {
        $this->sexe = $sexe;
    }

    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            [
                'mat_etudiant' => $this->matEtudiant,
                'personal_email' => $this->personalEmail,
                'adresse' => $this->adresse,
                'sexe' => $this->sexe->name
            ]
        );
    }

    public static function fromArray(array $data): Etudiant
    {
        return new Etudiant(
            $data['nom'],
            $data['prenom'],
            $data['email_of_school'],
            $data['password'],
            $data['mat_etudiant'],
            $data['personal_email'],
            $data['adresse'],
            Sexe::fromName($data['sexe'])
        );
    }

    public function toArrayView(): array
    {
        return array_merge(
            parent::toArrayView(),
            [
                'mat_etudiant' => $this->matEtudiant,
                'personal_email' => $this->personalEmail,
                'adresse' => $this->adresse,
                'sexe' => $this->sexe->value
            ]
        );
    }

    public function format(string $pattern = '{matricule} {nom} {prenom}'): string
    {
        $replace = [
            '{nom}' => $this->getNom(),
            '{prenom}' => $this->getPrenom(),
            '{email}' => $this->getEmailOfSchool(),
            '{role}' => $this->getRole()->value,
            '{matricule}' => $this->matEtudiant,
            '{email_perso}' => $this->personalEmail,
            '{adresse}' => $this->adresse,
            '{sexe}' => $this->sexe->value,
        ];

        return strtr($pattern, $replace);
    }
}
