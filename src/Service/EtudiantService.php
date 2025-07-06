<?php
declare(strict_types=1);

namespace App\Service;

use App\EnumDomain\Sexe;
use App\EnumForm\InputMessage;
use App\Model\Etudiant;
use App\Repository\EtudiantRepository;

class EtudiantService
{
    private EtudiantRepository $repo;
    private UtilisateurService $utilisateurService;
    private AnneeScolaireService $anneeService;

    public function __construct()
    {
        $this->repo = new EtudiantRepository();
        $this->utilisateurService = new UtilisateurService();
        $this->anneeService = new AnneeScolaireService();
    }

    public function getByEmailOfSchool(string $emailOfSchool): ?Etudiant
    {
        return $this->repo->selectUnique(['email_of_school' => $emailOfSchool]);
    }

    public function seConnecter(string $emailOfSchool, string $password): ?Etudiant
    {
        $user = $this->getByEmailOfSchool($emailOfSchool);
        if ($user !== null && $user->getPassword() === $password) {
            return $user;
        }
        return null;
    }

    public function existsByPersonalEmail(string $personalEmail): bool
    {
        return $this->getByPersonalEmail($personalEmail) !== null;
    }

    public function existsByMatricule(string $matricule): bool
    {
        return $this->getByMatricule($matricule) !== null;
    }

    public function getByPersonalEmail(string $personalEmail): ?Etudiant
    {
        return $this->repo->selectUnique(['personal_email' => $personalEmail]);
    }

    public function getByMatricule(string $matricule): ?Etudiant
    {
        return $this->repo->selectUnique(['mat_etudiant' => $matricule]);
    }

    /**
     * @param int $offset
     * @param int|null $limit
     * @return Etudiant[]
     */
    public function getEtudiants(
        string|null $matEtu = null,
        Sexe|null   $sexe = null,
        string|null $emailOfSchool = null,
        int         $offset = 0,
        int|null    $limit = null
    ): array
    {
        $filters = $this->makeFilters($matEtu, $sexe, $emailOfSchool);
        return $this->repo->select($filters, $offset, $limit);
    }

    public function getNbEtudiants(
        string|null $matEtu = null,
        Sexe|null   $sexe = null,
        string|null $emailOfSchool = null,
    ): int
    {
        $filters = $this->makeFilters($matEtu, $sexe, $emailOfSchool);
        return $this->repo->count($filters);
    }


    /**
     * @param string $sexe
     * @param int $offset
     * @param int|null $limit
     * @return Etudiant[]
     */
    public
    function getEtudiantsBySexe(string $sexe, int $offset = 0, int|null $limit = null): array
    {
        return $this->repo->select(['sexe' => $sexe], $offset, $limit);
    }

    /**
     * Génère un matricule étudiant unique au format ISM-YYYY/DK-N.
     * @return string
     */
    private
    function genererMatricule(): string
    {
        $anneeCourante = $this->anneeService->getAnneeCourante();
        $anneeFormat = $anneeCourante !== null ? $anneeCourante->format("{code}") : date('y') . date('y', strtotime('+1 year'));
        $base = "ISM-{$anneeFormat}/DK";
        $n = 1;
        do {
            $matricule = $base . '-' . $n;
            $n++;
        } while ($this->existsByMatricule($matricule));
        return $matricule;
    }

    function enregistrerEtudiant(Etudiant $etudiant): InputMessage
    {
        if ($this->existsByPersonalEmail($etudiant->getPersonalEmail())) {
            return InputMessage::ERROR_METIER_ETUDIANT_INVALIDE;
        }

        // Générer l'email d'école unique
        $emailEcole = $this->utilisateurService->genererEmailOfSchool(
            $etudiant->getPrenom(),
            $etudiant->getNom(),
            $etudiant->getRole()->value
        );
        // Générer le matricule unique
        $matricule = $this->genererMatricule();

        $nouveau = new Etudiant(
            nom: $etudiant->getNom(),
            prenom: $etudiant->getPrenom(),
            emailOfSchool: $emailEcole,
            password: $etudiant->getPassword(),
            matEtudiant: $matricule,
            personalEmail: $etudiant->getPersonalEmail(),
            adresse: $etudiant->getAdresse(),
            sexe: $etudiant->getSexe()
        );

        return $this->repo->insert($nouveau)
            ? InputMessage::SUCCESS_FINALE_ETUDIANT_CREATED
            : InputMessage::ERROR_FINALE_ETUDIANT_NOT_CREATED;
    }

    private function makeFilters(string|null $matEtu, Sexe|null $sexe, string|null $emailOfSchool): array
    {
        $filters = [];

        if ($matEtu !== null) {
            $filters['mat_etudiant'] = $matEtu;
        }
        if ($sexe !== null) {
            $filters['sexe'] = $sexe->name;
        }
        if ($emailOfSchool !== null) {
            $filters['email_of_school'] = $emailOfSchool;
        }
        return $filters;
    }
}
