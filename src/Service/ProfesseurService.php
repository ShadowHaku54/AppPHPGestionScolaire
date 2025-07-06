<?php
declare(strict_types=1);

namespace App\Service;

use App\Config\Validator;
use App\EnumDomain\GradeProfesseur;
use App\EnumForm\InputMessage;
use App\Model\Professeur;
use App\Repository\ProfesseurRepository;

class ProfesseurService
{
    private ProfesseurRepository $repo;

    public function __construct()
    {
        $this->repo = new ProfesseurRepository();
    }

    public function existsById(int $idProf): bool
    {
        return $this->getProfById($idProf) !== null;
    }

    public function ajouterProf(Professeur $professeur, Validator $validator): bool
    {
        return $this->repo->insert($professeur);
    }

    /**
     * @param int $offset
     * @param int|null $limit
     * @return Professeur[]
     */
    public function getProfs(
        int|null $id = null,
        string|null $nomProf = null,
        GradeProfesseur|null $gradeProfesseur = null,
        int $offset = 0,
        ?int $limit = null
    ): array
    {
        $filters = $this->makeFilters($id, $nomProf, $gradeProfesseur);
        return $this->repo->select($filters, $offset, $limit);
    }

    private function makeFilters(
        int|null $id = null,
        string|null $nomProf = null,
        GradeProfesseur|null $gradeProfesseur = null,
    )
    {
        $filters = [];
        if ($id !== null) {
            $filters['id_professeur'] = $id;
        }
        if ($nomProf !== null) {
            $filters['nom'] = $nomProf;
        }
        if ($gradeProfesseur !== null) {
            $filters['grade'] = $gradeProfesseur->name;
        }
        return $filters;
    }

    public function getNbProfs(
        int|null $id = null,
        string|null $nomProf = null,
        GradeProfesseur|null $gradeProfesseur = null,
    )
    {
        $filters = $this->makeFilters($id, $nomProf, $gradeProfesseur);
        return $this->repo->count($filters);
    }


    public function getProfById(int $id): Professeur|null
    {
        return $this->repo->selectUnique(['id_professeur' => $id]);
    }


    /**
     * @param GradeProfesseur $grade
     * @param int $offset
     * @param int|null $limit
     * @return Professeur[]
     */
    public function getProfsByGrade(GradeProfesseur $grade, int $offset = 0, ?int $limit = null): array
    {
        return $this->repo->select(['grade' => $grade->value], $offset, $limit);
    }

    /**
     * @param string $nom
     * @param int $offset
     * @param int|null $limit
     * @return Professeur[]
     */
    public function getProfsByNom(string $nom, int $offset = 0, ?int $limit = null): array
    {
        return $this->repo->select(['nom' => $nom], $offset, $limit);
    }
}
