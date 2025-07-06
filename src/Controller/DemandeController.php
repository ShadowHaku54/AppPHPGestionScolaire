<?php

namespace App\Controller;

use App\Config\Controller;
use App\EnumDomain\Role;
use App\EnumDomain\StatutDemande;
use App\EnumDomain\StatutInscription;
use App\EnumDomain\TypeDemande;
use App\Model\Demande;
use App\Model\Etudiant;
use App\Model\Utilisateur;
use App\Service\AnneeScolaireService;
use App\Service\DemandeService;
use App\Service\EtudiantService;
use App\Service\InscriptionService;

class DemandeController extends Controller
{
    private DemandeService $demandeService;
    private AnneeScolaireService $anneeScolaireService;
    private EtudiantService $etudiantService;
    private InscriptionService $inscriptionService;

    public function __construct()
    {
        parent::__construct();
        $this->demandeService = new DemandeService();
        $this->anneeScolaireService = new AnneeScolaireService();
        $this->etudiantService = new EtudiantService();
        $this->inscriptionService = new InscriptionService();
        $this->handleRequest();
    }

    protected function handleRequest(): void
    {
        $action = $_REQUEST["action"] ?? 'show_list';
        switch ($action) {
            case 'show_list':
                $this->showList();
                break;
            case 'show_form':
                $this->showForm();
                break;
            case 'post_form':
                $this->postForm();
                break;
            default:
                $controller = new ErrorController();
                break;
        }
    }

    private function showList(): void
    {
        /** @var Utilisateur|Etudiant $utilisateur */
        $utilisateur = $_SESSION["utilisateur"];
        $isStudent = ($utilisateur->getRole() === Role::ETU);
        extract($_REQUEST);
        if (isset($idAcceptDemande) && $this->validator->isInt($idAcceptDemande) !== null) {
            $this->demandeService->traiterDemande($idAcceptDemande, true);
        } elseif (isset($idDeclineDemande)) {
            $this->demandeService->traiterDemande($idDeclineDemande, false);
        } elseif (isset($idRenewDemande)) {
            $this->demandeService->renouvellerDemande($idRenewDemande);
        }

        $idDemande = null;
        $matEtudiant = null;
        $typeDemande = isset($type) ? TypeDemande::tryFrom($type) : null;
        $statutDemande = isset($statut) ? StatutDemande::tryFrom($statut) : null;
        $idAnneeScolaire = (isset($idAnneeCourante)) ? $idAnneeCourante : $this->anneeScolaireService->getAnneeCourante()->getIdAnneeScolaire();
        $limit = isset($per_page) ? $this->validator->isInt($per_page) : 10;
        $offset = 0;
        if (isset($page) && $this->validator->isInt($page) !== null) {
            $offset = ((int)$page - 1) * $limit;
        }
        if (!(empty($search))) {
            $idDemande = $this->validator->isInt($search);
            $matEtudiant = (!$isStudent) ? $this->validator->isMatriculeValid($search) : null;
            if ($idDemande === null && $matEtudiant === null) {
                $this->error("Mauvaise valeur de recherche");
            }
        }

        $demandesView = [];
        if ($utilisateur->getRole() === Role::ETU) {
            $demandes = $this->demandeService->getDemandes($idDemande, $utilisateur->getMatEtudiant(), $statutDemande, $typeDemande, $idAnneeScolaire, $offset, $limit);
            foreach ($demandes as $demande) {
                $demandesView[] = $demande->toArrayView();
            }
            $totalDemandes = $this->demandeService->getNbDemandes($idDemande, $utilisateur->getMatEtudiant(), $statutDemande, $typeDemande, $idAnneeScolaire);
        } else {
            $demandes = $this->demandeService->getDemandes($idDemande, $matEtudiant, $statutDemande, $typeDemande, $idAnneeScolaire, $offset, $limit);
            foreach ($demandes as $demande) {
                $demandeVw = $demande->toArrayView();
                $etudiant = $this->etudiantService->getByMatricule($demande->getMatEtudiant());
                $demandeVw["etudiant"] = $etudiant->format();
                $demandesView[] = $demandeVw;
            }
            $totalDemandes = $this->demandeService->getNbDemandes($idDemande, $matEtudiant, $statutDemande, $typeDemande, $idAnneeScolaire);
        }
        $_SESSION["demandesView"] = $demandesView;
        $_SESSION["totalDemandes"] = $totalDemandes;
        $_SESSION["annees"] = $this->anneeScolaireService->getAnnees();
        $this->render("demande/list.html.php", "demande/list.css", "demande/list.js");
    }


    private function showForm()
    {
        /** @var Etudiant $etudiant */
        $etudiant = $_SESSION["utilisateur"];
        $annee = $this->anneeScolaireService->getAnneeCourante();
        $inscription = $this->inscriptionService->getByMatEtudiantAndIdAnneeScolaire($etudiant->getMatEtudiant(), $annee->getIdAnneeScolaire());
        if ($inscription === null || $inscription->getStatut() !== StatutInscription::VALIDEE) {
            $this->validator->addError("noCurrentInscription", "Vous n'avez aucune inscription valide en cours");
            $inscription = null;
        }
        $this->notification("Vous pouvez effectuer une demande", "Vous n'avez pas le droit d'effectuer une demande");
        $_SESSION["inscription"] = $inscription;
        $_SESSION["annee"] = $annee;
        $this->render("demande/form.html.php", "demande/form.css", "demande/form.js");
    }

    private function postForm(): void
    {
        /** @var Etudiant $etudiant */
        $etudiant = $_SESSION["utilisateur"];
        extract($_POST);
        $this->validator->isEmpty("motifEmpty", $motif, "Le motif ne doit pas être vide");
        $typeDemande = TypeDemande::tryFrom($type);
        $this->validator->isNull("typeError", $typeDemande, "Le type demande n'est pas valide");
        $idInscription = $this->validator->isInt($idInscription);
        $this->validator->isNull("inscriptionError", $typeDemande, "L'inscription n'est pas valide");
        $this->notification("Votre demande a été envoyé", "Une erreur est survenue");
        $pass = true;
        if ($this->validator->isValid()) {
            $pass = $this->demandeService->enregistrerDemande(
                new Demande(
                    null,
                    null,
                    null,
                    $etudiant->getMatEtudiant(),
                    $idInscription,
                    $typeDemande,
                    $motif,
                    null),
                $this->validator
            );
            $this->notification("Votre demande a été envoyé", "Une erreur est survenue");
            if (!$pass) {
                $this->error("Une erreur est survenue");
            }
        }
        if ($this->validator->isValid() && $pass) {
            $this->showList();
        }
        $this->showForm();
    }


}