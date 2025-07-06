<?php

namespace App\Controller;

use App\Config\Controller;
use App\EnumDomain\Role;
use App\EnumDomain\StatutInscription;
use App\EnumDomain\TypeInscription;
use App\Model\AnneeScolaire;
use App\Model\Etudiant;
use App\Model\Utilisateur;
use App\Service\AnneeScolaireService;
use App\Service\ClasseService;
use App\Service\EtudiantService;
use App\Service\InscriptionService;

class InscriptionController extends Controller
{
    private InscriptionService $inscriptionService;
    private AnneeScolaireService $anneeScolaireService;
    private EtudiantService $etudiantService;
    private ClasseService $classeService;

    public function __construct()
    {
        parent::__construct();
        $this->inscriptionService = new InscriptionService();
        $this->anneeScolaireService = new AnneeScolaireService();
        $this->etudiantService = new EtudiantService();
        $this->classeService = new ClasseService();
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
            default:
                $controller = new ErrorController();
                break;
        }
    }

    private function showList(): void
    {
        /** @var Utilisateur|Etudiant $utilisateur */
        $utilisateur = $_SESSION["utilisateur"];
        $isStudent = $utilisateur->getRole() === Role::ETU;
        extract($_POST);
        $idInscription = null;
        $matEtudiant = null;
        $typeInscription = isset($type) ? TypeInscription::tryFrom($type) : null;
        $statutInscription = isset($statut) ? StatutInscription::tryFrom($statut) : null;
        $idAnneeScolaire = (isset($idAnneeCourante)) ? $idAnneeCourante : $this->anneeScolaireService->getAnneeCourante()->getIdAnneeScolaire();
        $limit = isset($per_page) ? $this->validator->isInt($per_page) : 10;
        $offset = 0;
        if (isset($page) && $this->validator->isInt($page) !== null) {
            $offset = ((int)$page - 1) * $limit;
        }
        if (!(empty($search))) {
            $idInscription = $this->validator->isInt($search);
            $matEtudiant = (!$isStudent) ? $this->validator->isMatriculeValid($search) : null;
            if ($idInscription === null && $matEtudiant === null) {
                $this->error("Mauvaise valeur de recherche");
            }
        }

        $inscriptionsView = [];
        if ($isStudent) {
            $inscriptions = $this->inscriptionService->getInscriptions(
                idInscription: $idInscription,
                matEtu: $utilisateur->getMatEtudiant(),
                statut: $statutInscription,
                type: $typeInscription,
                offset: $offset,
                limit: $limit);
            foreach ($inscriptions as $inscription) {
                $inscriptionVw = $inscription->toArrayView();
                $classe = $this->classeService->getById($inscription->getIdClasse());
                $anneeScolaire = $this->anneeScolaireService->getAnneeById($inscription->getIdAnneeScolaire());
                $inscriptionVw["classe"] = $classe->format();
                $inscriptionVw["anneeScolaire"] = $anneeScolaire->format();
                $inscriptionsView[] = $inscriptionVw;
            }
            $totalInscriptions = $this->inscriptionService->getNbInscriptions(
                idInscription: $idInscription,
                matEtu: $utilisateur->getMatEtudiant(),
                statut: $statutInscription);
        } else {
            $inscriptions = $this->inscriptionService->getInscriptions(
                idInscription: $idInscription,
                matEtu: $matEtudiant,
                idAnneeScolaire: $idAnneeScolaire,
                statut: $statutInscription,
                type: $typeInscription,
                offset: $offset,
                limit: $limit);
            foreach ($inscriptions as $inscription) {
                $inscriptionVw = $inscription->toArrayView();
                $etudiant = $this->etudiantService->getByMatricule($inscription->getMatEtudiant());
                $classe = $this->classeService->getById($inscription->getIdClasse());
                $inscriptionVw["etudiant"] = $etudiant->format();
                $inscriptionVw["classe"] = $classe->format();
                $inscriptionsView[] = $inscriptionVw;
            }
            $totalInscriptions = $this->inscriptionService->getNbInscriptions(
                idInscription: $idInscription,
                matEtu: $matEtudiant,
                idAnneeScolaire: $idAnneeScolaire,
                statut: $statutInscription,
                type: $typeInscription
            );
        }
        $_SESSION["inscriptionsView"] = $inscriptionsView;
        $_SESSION["totalInscriptions"] = $totalInscriptions;
        $_SESSION["annees"] = $this->anneeScolaireService->getAnnees();

        $this->render("inscription/list.html.php", "inscription/list.css", "inscription/list.js");
    }

    private function showForm()
    {
        $this->render("inscription/form.html.php", "inscription/form.css", "inscription/form.js");
    }


}