<?php
declare(strict_types=1);

namespace App\Service;

use App\Config\Validator;
use App\EnumDomain\StatutDemande;
use App\EnumDomain\StatutInscription;
use App\EnumDomain\TypeDemande;
use App\EnumDomain\TypeInscription;
use App\EnumForm\InputMessage;
use App\Model\Demande;
use App\Repository\DemandeRepository;

class DemandeService
{
    private DemandeRepository $repo;
    private InscriptionService $inscriptionService;
    private AnneeScolaireService $anneeService;

    public function __construct()
    {
        $this->repo = new DemandeRepository();
        $this->inscriptionService = new InscriptionService();
        $this->anneeService = new AnneeScolaireService();
    }

    public function existById(int $id): bool
    {
        return $this->getById($id) !== null;
    }

    public function getById(int $id): ?Demande
    {
        return $this->repo->selectUnique(['id_demande' => $id]);
    }

    public function enregistrerDemande(Demande $demande, Validator $validator): bool
    {
        $inscription = $this->inscriptionService->getById($demande->getIdInscription());
        $anneeCourante = $this->anneeService->getAnneeCourante();


        if ($inscription === null || $inscription->getIdAnneeScolaire() !== $anneeCourante->getIdAnneeScolaire()) {
            $validator->addError("inscriptionError", "L'inscription est invalide");
        }

        if ($inscription->getMatEtudiant() !== $demande->getMatEtudiant()) {
            $validator->addError("inscriptionError", "Inscription correspond pas");
        }

        if(!$validator->isValid()){
            return false;
        }

        $demandePropre = new Demande(
            $demande->getIdDemande(),
            $demande->getDateDemande(),
            $anneeCourante->getIdAnneeScolaire(),
            $demande->getMatEtudiant(),
            $demande->getIdInscription(),
            $demande->getType(),
            $demande->getMotif(),
            $demande->getStatut(),
        );

        return $this->repo->insert($demandePropre);
    }

    /**
     * @param int|null $id
     * @param string|null $matEtu
     * @param StatutDemande|null $statut
     * @param TypeDemande|null $type
     * @param int|null $idAnneeScolaire
     * @param int $offset
     * @param int|null $limit
     * @return Demande[]
     */
    public function getDemandes(
        int|null      $id = null,
        string|null   $matEtu = null,
        StatutDemande $statut = null,
        TypeDemande   $type = null,
        int           $idAnneeScolaire = null,
        int           $offset = 0,
        int|null      $limit = null
    ): array
    {
        $filters = $this->makeFilters($id, $matEtu, $statut, $type, $idAnneeScolaire);
        return $this->repo->select($filters, $offset, $limit);
    }

    private function makeFilters
    (
        int|null      $id = null,
        string|null   $matEtu = null,
        StatutDemande $statut = null,
        TypeDemande   $type = null,
        int           $idAnneeScolaire = null
    ): array
    {
        $filters = [];

        if ($id !== null) {
            $filters['id_demande'] = $id;
        }
        if ($matEtu !== null) {
            $filters['mat_etudiant'] = $matEtu;
        }
        if ($statut !== null) {
            $filters['statut'] = $statut->name;
        }
        if ($type !== null) {
            $filters['type'] = $type->name;
        }
        if ($idAnneeScolaire !== null) {
            $filters['id_annee_scolaire'] = $idAnneeScolaire;
        }
        return $filters;
    }

    public function getNbDemandes
    (
        int|null      $id = null,
        string|null   $matEtu = null,
        StatutDemande $statut = null,
        TypeDemande   $type = null,
        int           $idAnneeScolaire = null
    ): int
    {
        return $this->repo->count($this->makeFilters($id, $matEtu, $statut, $type, $idAnneeScolaire));
    }

    public function traiterDemande(int $idDemande, bool $accepter):bool{
        $statutDemande = $accepter ? StatutDemande::ACCEPTEE : StatutDemande::REFUSE;
        $demande = $this->getById($idDemande);
        $ok = false;
        if($demande !== null){
            $statutInscription = $demande->getType() === TypeDemande::ANNULATION ? StatutInscription::ANNULEE : StatutInscription::SUSPENDUE;
            $ok = $this->inscriptionService->updateStatut($demande->getIdInscription(), $statutInscription);
            debug($demande, $statutInscription, $ok);
            if($ok){
                $ok = $this->repo->update($idDemande, ["statut" => $statutDemande->name]);
            }
        }
        return $ok;
    }

    public function renouvellerDemande(int $idDemande):bool{
        $statutDemande = StatutDemande::EN_ATTENTE;
        $demande = $this->getById($idDemande);
        $ok = false;
        if($demande !== null && $demande->getStatut() !== $statutDemande){
            $statutInscription = StatutInscription::VALIDEE;
            $ok = $this->inscriptionService->updateStatut($demande->getIdInscription(), $statutInscription);
            if($ok){
                $ok = $this->repo->update($idDemande, ["statut" => $statutDemande->name]);
            }
        }
        return $ok;
    }
}
