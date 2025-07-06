<?php
declare(strict_types=1);

namespace App\Service;

use App\Config\Validator;
use App\EnumForm\InputMessage;
use App\Model\AnneeScolaire;
use App\Repository\AnneeScolaireRepository;

class AnneeScolaireService
{
    private static ?AnneeScolaire $anneeCourante = null;
    private AnneeScolaireRepository $repo;

    public function __construct()
    {
        $this->repo = new AnneeScolaireRepository();
    }


    public function definirNouvelleAnnee(AnneeScolaire $anneeScolaire, Validator $validator): bool
    {
        if ($this->existsByDebut($anneeScolaire->getDebut())) {
            $validator->addError("anneeAlreadyExist", "Cette année existe déjà.");
        }
        if ($validator->isValid() && $this->repo->insert($anneeScolaire)) {
            self::$anneeCourante = $anneeScolaire;
            return true;
        }
        return false;
    }

    private function existsByDebut(int $debut): bool
    {
        $annee = $this->repo->selectUnique(['debut' => $debut]);
        return $annee !== null;
    }


    public function getAnneeCourante(): AnneeScolaire|null
    {
        if (self::$anneeCourante === null) {
            self::$anneeCourante = $this->repo->selectLast();
        }
        return self::$anneeCourante;
    }


    /**
     * @param int $offset
     * @param int|null $limit
     * @return AnneeScolaire[]
     */
    public function getAnnees(int|null $id = null, int|null $debut = null, int $offset = 0, int $limit = null): array
    {
        $filters = $this->makeFilters();
        return $this->repo->select($filters, offset: $offset, limit: $limit);
    }

    private function makeFilters(int|null $id = null, int|null $debut = null): array
    {
        $filters = [];
        if ($id !== null) {
            $filters['id_annee_scolaire'] = $id;
        }
        if ($debut !== null) {
            $filters['debut'] = $debut;
        }
        return $filters;
    }

    public function getNbAnneeScolaire(int|null $id = null, int|null $debut = null): int
    {
        $filters = $this->makeFilters($id, $debut);
        return $this->repo->count($filters);
    }


    public function getAnneeById(int $id): AnneeScolaire|null
    {
        return $this->repo->selectUnique(['id_annee_scolaire' => $id]);
    }

    /**
     * @return AnneeScolaire[]
     */
    public function getAnneeByIds(array $ids): array
    {
        /** @var AnneeScolaire[] $anneesScolaire */
        $anneesScolaire = [];
        foreach ($ids as $id) {
            $anneesScolaire[] = $this->getAnneeById($id);
        }
        return $anneesScolaire;
    }
}
