<?php
declare(strict_types=1);

namespace App\Service;

use App\EnumDomain\StatutInscription;
use App\EnumDomain\TypeInscription;
use App\EnumForm\InputMessage;
use App\Model\Inscription;
use App\Repository\InscriptionRepository;

class InscriptionService
{
    private InscriptionRepository $repo;
    private EtudiantService $etudiantService;
    private ClasseService $classeService;
    private AnneeScolaireService $anneeService;

    public function __construct()
    {
        $this->repo = new InscriptionRepository();
        $this->etudiantService = new EtudiantService();
        $this->classeService = new ClasseService();
        $this->anneeService = new AnneeScolaireService();
    }

    /**
     * Enregistre une inscription après toutes les vérifications métier.
     */
    public function enregistrerInscription(Inscription $inscription): InputMessage
    {
        if (!$this->etudiantService->existsByMatricule($inscription->getMatEtudiant())) {
            return InputMessage::ERROR_METIER_ETUDIANT_INVALIDE;
        }

        if (!$this->classeService->existById($inscription->getIdClasse())) {
            return InputMessage::ERROR_METIER_CLASSE_NOT_EXIST;
        }

        $anneeCourante = $this->anneeService->getAnneeCourante();

        $deja = $this->getByMatEtudiantAndIdAnneeScolaire(
            $inscription->getMatEtudiant(),
            $anneeCourante->getIdAnneeScolaire()
        );

        if ($deja !== null) {
            return InputMessage::ERROR_METIER_INSCRIPTION_NOT_EXIST;
        }

        $nouvelle = new Inscription(
            idInscription: null,
            dateInscription: new \DateTime(),
            idAnneeScolaire: $anneeCourante->getIdAnneeScolaire(),
            matEtudiant: $inscription->getMatEtudiant(),
            idClasse: $inscription->getIdClasse(),
            type: $inscription->getType()
        );

        return $this->repo->insert($nouvelle)
            ? InputMessage::SUCCESS_FINALE_INSCRIPTION_CREATED
            : InputMessage::ERROR_FINALE_INSCRIPTION_NOT_CREATED;
    }

    public function getById(int $id): ?Inscription
    {
        return $this->repo->selectUnique(['id_inscription' => $id]);
    }

    public function getByMatEtudiantAndIdAnneeScolaire(string $matEtudiant, int $idAnneeScolaire): ?Inscription
    {
        return $this->repo->selectUnique([
            'mat_etudiant' => $matEtudiant,
            'id_annee_scolaire' => $idAnneeScolaire
        ]);
    }

    /**
     * @param int|null $idInscription
     * @param string|null $matEtu
     * @param int|null $idClasse
     * @param int|null $idAnneeScolaire
     * @param StatutInscription|null $statut
     * @param TypeInscription|null $type
     * @param int $offset
     * @param int|null $limit
     * @return Inscription[]
     */
    public function getInscriptions(
        int|null          $idInscription = null,
        string|null       $matEtu = null,
        int|null          $idClasse = null,
        int|null          $idAnneeScolaire = null,
        StatutInscription $statut = null,
        TypeInscription   $type = null,
        int               $offset = 0,
        int|null          $limit = null
    ): array
    {
        $filters = $this->makeFilters($idInscription, $matEtu, $idClasse, $idAnneeScolaire, $statut, $type);


        return $this->repo->select($filters, $offset, $limit);
    }

    private function makeFilters
    (
        int|null          $idInscription = null,
        string|null       $matEtu = null,
        int|null          $idClasse = null,
        int|null          $idAnneeScolaire = null,
        StatutInscription $statut = null,
        TypeInscription   $type = null,
    ): array
    {
        $filters = [];

        if ($idInscription !== null) {
            $filters['id_inscription'] = $idInscription;
        }
        if ($matEtu !== null) {
            $filters['mat_etudiant'] = $matEtu;
        }
        if ($idClasse !== null) {
            $filters['id_classe'] = $idClasse;
        }
        if ($idAnneeScolaire !== null) {
            $filters['id_annee_scolaire'] = $idAnneeScolaire;
        }
        if ($statut !== null) {
            $filters['statut'] = $statut->name;
        }
        if ($type !== null) {
            $filters['type'] = $type->name;
        }
        return $filters;
    }

    public function updateStatut(int $id, StatutInscription $statutInscription): bool
    {
        return $this->repo->update($id, ['statut' => $statutInscription->name]);
    }

    public function getNbInscriptions(
        int|null          $idInscription = null,
        string|null       $matEtu = null,
        int|null          $idClasse = null,
        int|null          $idAnneeScolaire = null,
        StatutInscription $statut = null,
        TypeInscription   $type = null,
    )
    {
        $filters = $this->makeFilters($idInscription, $matEtu, $idClasse, $idAnneeScolaire, $statut, $type);
        return $this->repo->count($filters);
    }
}
