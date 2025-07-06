<?php
declare(strict_types=1);

namespace App\Service;

use App\EnumForm\InputMessage;
use App\Model\Affectation;
use App\Model\Classe;
use App\Model\Professeur;
use App\Repository\AffectationRepository;

class AffectationService
{
    private AffectationRepository $repo;
    private ProfesseurService $profService;
    private ClasseService $classeService;

    public function __construct()
    {
        $this->repo = new AffectationRepository();
        $this->profService = new ProfesseurService();
        $this->classeService = new ClasseService();
    }

    public function getAffectationByProfEtClasse(int $idProf, int $idClasse): ?Affectation
    {
        return $this->repo->selectUnique(['id_professeur' => $idProf, 'id_classe' => $idClasse]);
    }

    public function existsByIdProfAndIdClasse(int $idProf, int $idClasse): bool
    {
        return $this->getAffectationByProfEtClasse($idProf, $idClasse) !== null;
    }

    public function enregistrerAffectation(Affectation $affectation): InputMessage
    {
        if (!$this->profService->existsById($affectation->getIdProfesseur())) {
            return InputMessage::ERROR_METIER_PROF_NOT_EXIST;
        }
        if (!$this->classeService->existById($affectation->getIdClasse())) {
            return InputMessage::ERROR_METIER_CLASSE_NOT_EXIST;
        }
        if ($this->existsByIdProfAndIdClasse($affectation->getIdProfesseur(), $affectation->getIdClasse())) {
            return InputMessage::ERROR_METIER_AFFECTATION_INVALIDE;
        }

        return $this->repo->insert($affectation)
            ? InputMessage::SUCCESS_FINALE_AFFECTATION_CREATED
            : InputMessage::ERROR_FINALE_AFFECTATION_NOT_CREATED;
    }

    public function getAffectationById(int $id): ?Affectation
    {
        return $this->repo->selectUnique(['id_affectation' => $id]);
    }

    /**
     * @param int $offset
     * @param int|null $limit
     * @return Affectation[]
     */
    public function getAffectations(int $offset = 0, int|null $limit = null): array
    {
        return $this->repo->select([], $offset, $limit);
    }

    /**
     * @param int $idProf
     * @param int $offset
     * @param int|null $limit
     * @return Affectation[]
     */
    public function getAffectationsByProf(int $idProf, int $offset = 0, int|null $limit = null): array
    {
        return $this->repo->select(['id_professeur' => $idProf], $offset, $limit);
    }

    /**
     * @param int $idClasse
     * @param int $offset
     * @param int|null $limit
     * @return Affectation[]
     */
    public function getAffectationsByClasse(int $idClasse, int $offset = 0, int|null $limit = null): array
    {
        return $this->repo->select(['id_classe' => $idClasse], $offset, $limit);
    }

    /**
     * @param int $idClasse
     * @param int $offset
     * @param int|null $limit
     * @return Professeur[]
     */
    public function getProfsByClasse(int $idClasse, int $offset = 0, int|null $limit = null): array
    {
        $affectations = $this->getAffectationsByClasse($idClasse, $offset, $limit);
        $profs = [];
        foreach ($affectations as $aff) {
            $prof = $this->profService->getProfById($aff->getIdProfesseur());
            if ($prof !== null) {
                $profs[] = $prof;
            }
        }
        return $profs;
    }

    /**
     * @param int $idProf
     * @param int $offset
     * @param int|null $limit
     * @return Classe[]
     */
    public function getClassesByProf(int $idProf, int $offset = 0, int|null $limit = null): array
    {
        $affectations = $this->getAffectationsByProf($idProf, $offset, $limit);
        $classes = [];
        foreach ($affectations as $aff) {
            $classe = $this->classeService->getById($aff->getIdClasse());
            if ($classe !== null) {
                $classes[] = $classe;
            }
        }
        return $classes;
    }

}
