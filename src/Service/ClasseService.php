<?php
declare(strict_types=1);

namespace App\Service;

use App\Config\Validator;
use App\EnumDomain\Filiere;
use App\EnumDomain\Niveau;
use App\EnumForm\InputMessage;
use App\Model\Classe;
use App\Repository\ClasseRepository;

class ClasseService
{
    private ClasseRepository $repo;
    private AnneeScolaireService $anneeService;

    public function __construct()
    {
        $this->repo = new ClasseRepository();
        $this->anneeService = new AnneeScolaireService();
    }

    public function existByLibelleAndAnneeScolaire(string $libelle, int $idAnneeScolaire): bool
    {
        return $this->getByLibelleAndAnneeScolaire($libelle, $idAnneeScolaire) !== null;
    }

    public function existById(int $id): bool
    {
        return $this->getById($id) !== null;
    }

    public function enregistrerClasse(Classe $classe, Validator $validator): bool
    {
        $thisYear = $this->anneeService->getAnneeCourante();
        if ($this->existByLibelleAndAnneeScolaire($classe->getLibelle(), $thisYear->getIdAnneeScolaire())) {
            $validator->addError("libelleAlreadyExist", "Cet libellé existe déjà pour cette annee scolaire");
            return false;
        }

        $classe = new Classe(
            null,
            $classe->getLibelle(),
            $classe->getNiveau(),
            $classe->getFiliere(),
            $classe->getDebutInscription(),
            $classe->getFinInscription(),
            $thisYear->getIdAnneeScolaire(),
        );

        return $this->repo->insert($classe);
    }

    public function getById(int $id): ?Classe
    {
        return $this->repo->selectUnique(['id_classe' => $id]);
    }

    public function getByLibelleAndAnneeScolaire(string $libelle, int $idAnneeScolaire): ?Classe
    {
        return $this->repo->selectUnique([
            'libelle' => $libelle,
            'id_annee_scolaire' => $idAnneeScolaire
        ]);
    }

    /**
     * @param int|null $id
     * @param int|null $libelle
     * @param int|null $idAnneeScolaire
     * @param Niveau|null $niveau
     * @param Filiere|null $filiere
     * @param int $offset
     * @param int|null $limit
     * @return Classe[]
     */
    public function getClasses(
        int|null $id = null,
        string|null $libelle = null,
        int|null $idAnneeScolaire = null,
        Niveau|null $niveau = null,
        Filiere|null $filiere = null,
        int $offset = 0,
        int|null $limit = null
    ): array {
        $filters = $this->makeFilters($id, $libelle, $idAnneeScolaire, $niveau, $filiere);
        return $this->repo->select($filters, $offset, $limit);
    }

    private function makeFilters
    (
        int|null $id = null,
        string|null $libelle = null,
        int|null $idAnneeScolaire = null,
        Niveau|null $niveau = null,
        Filiere|null $filiere = null,
    ): array
    {
        $filters = [];
        if ($id !== null) {
            $filters['id_classe'] = $id;
        }
        if ($libelle !== null) {
            $filters['libelle'] = $libelle;
        }
        if ($idAnneeScolaire !== null) {
            $filters['id_annee_scolaire'] = $idAnneeScolaire;
        }
        if ($niveau !== null) {
            $filters['niveau'] = $niveau->name;
        }
        if ($filiere !== null) {
            $filters['filiere'] = $filiere->name;
        }
        return $filters;
    }

    public function getNbClasses(
        int|null $id = null,
        string|null $libelle = null,
        int|null $idAnneeScolaire = null,
        Niveau|null $niveau = null,
        Filiere|null $filiere = null,
    )
    {
        $filters = $this->makeFilters($id, $libelle, $idAnneeScolaire, $niveau, $filiere);
        return $this->repo->count($filters);
    }

    /**
     * @param int $offset
     * @param int|null $limit
     * @return Classe[]
     */
    public function getClassesAnneeCourante(int $offset = 0, int|null $limit = null): array
    {
        $annee = $this->anneeService->getAnneeCourante();
        if ($annee === null) {
            return [];
        }
        return $this->repo->select(['id_annee_scolaire' => $annee->getIdAnneeScolaire()], $offset, $limit);
    }
}
