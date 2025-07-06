<?php

namespace App\Controller;

use App\Config\Controller;
use App\EnumDomain\Sexe;
use App\Model\AnneeScolaire;
use App\Model\Etudiant;
use App\Model\Utilisateur;
use App\Service\AnneeScolaireService;
use App\Service\DemandeService;
use App\Service\EtudiantService;
use App\Service\InscriptionService;

class AnneeScolaireController extends Controller
{
    private AnneeScolaireService $anneeScolaireService;
    private InscriptionService $inscriptionService;
    private EtudiantService $etudiantService;
    private DemandeService $demandeService;


    public function __construct()
    {
        parent::__construct();
        $this->anneeScolaireService = new AnneeScolaireService();
        $this->inscriptionService = new InscriptionService();
        $this->etudiantService = new EtudiantService();
        $this->demandeService = new DemandeService();
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
        extract($_POST);
        $idAnneeScolaire = null;
        if (!(empty($search))) {
            $idAnneeScolaire = $this->validator->isInt($search);
            if ($idAnneeScolaire === null) {
                $this->error("Mauvaise valeur de recherche");
            }
        }

        $anneesScolaireView = [];
        $anneeScolaires = $this->anneeScolaireService->getAnnees($idAnneeScolaire);
        foreach ($anneeScolaires as $anneeScolaire) {
            $anneeScolaireVw = $anneeScolaire->toArrayView();
            $inscriptions = $this->inscriptionService->getInscriptions(idAnneeScolaire:$anneeScolaire->getIdAnneeScolaire());
            $nbGarcons = 0;
            $nbFilles = 0;
            foreach ($inscriptions as $inscription) {
                $etudiant = $this->etudiantService->getByMatricule($inscription->getMatEtudiant());
                if ($etudiant !== null) {
                    if ($etudiant->getSexe() === Sexe::F) {
                        $nbFilles++;
                    } else {
                        $nbGarcons++;
                    }
                }
            }
            $nbDemandes = $this->demandeService->getNbDemandes(idAnneeScolaire:$anneeScolaire->getIdAnneeScolaire());
            $anneeScolaireVw["nbFilles"] = $nbFilles;
            $anneeScolaireVw["nbGarcons"] = $nbGarcons;
            $anneeScolaireVw["effectif"] = $nbFilles + $nbGarcons;
            $anneeScolaireVw["nbDemandes"] = $nbDemandes;
            $anneesScolaireView[] = $anneeScolaireVw;
        }
        $_SESSION["anneesScolaireView"] = $anneesScolaireView;
        $_SESSION["annees"] = $this->anneeScolaireService->getAnnees();

        $this->render("annee-scolaire/list.html.php", "inscription/list.css", "annee-scolaire/list.js");
    }

    private function showForm()
    {
        $this->render("annee-scolaire/form.html.php", "annee-scolaire/form.css", "annee-scolaire/form.js");
    }

    private function postForm(): void
    {
        extract($_POST);
        $this->anneeScolaireService->definirNouvelleAnnee(new AnneeScolaire(null, (int)$debut), $this->validator);
        $this->notification("Nouvelle année scolaire définie", "Une erreur est survenue lors de l'insertion.");
        if ($this->validator->isValid()) {
            $this->showList();
        }
        $this->showForm();
    }
}