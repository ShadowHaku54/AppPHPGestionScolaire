<?php

namespace App\Controller;

use App\Config\Controller;
use App\EnumDomain\Filiere;
use App\EnumDomain\Niveau;
use App\EnumDomain\Sexe;
use App\Model\Classe;
use App\Model\Etudiant;
use App\Model\Utilisateur;
use App\Service\AnneeScolaireService;
use App\Service\ClasseService;
use App\Service\EtudiantService;
use App\Service\InscriptionService;
use DateMalformedStringException;
use DateTime;

class ClasseController extends Controller
{
    private ClasseService $classeService;
    private AnneeScolaireService $anneeScolaireService;
    private EtudiantService $etudiantService;
    private InscriptionService $inscriptionService;

    public function __construct()
    {
        parent::__construct();
        $this->classeService = new ClasseService();
        $this->anneeScolaireService = new AnneeScolaireService();
        $this->etudiantService = new EtudiantService();
        $this->inscriptionService = new InscriptionService();
        $this->handleRequest();
    }

    private function handleRequest(): void
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

    private function showList()
    {
        extract($_POST);
        $idClasse = null;
        $libelle = null;
        $niveau = isset($niveau) ? Niveau::tryFrom($niveau) : null;
        $filiere = isset($filiere) ? Filiere::tryFrom($filiere) : null;
        $idAnneeScolaire = (isset($idAnneeCourante)) ? $idAnneeCourante : $this->anneeScolaireService->getAnneeCourante()->getIdAnneeScolaire();
        $limit = isset($per_page) ? $this->validator->isInt($per_page) : 10;
        $offset = 0;
        if (isset($page) && $this->validator->isInt($page) !== null) {
            $offset = ((int)$page - 1) * $limit;
        }
        if (!(empty($search))) {
            $idClasse = $this->validator->isInt($search);
            if ($idClasse === null) {
                $libelle = $search;
            }
        }

        $todayDate = new DateTime();
        $classesView = [];
        $classes = $this->classeService->getClasses($idClasse, $libelle, $idAnneeScolaire, $niveau, $filiere, $offset, $limit);
        foreach ($classes as $classe) {
            $classeVw = $classe->toArrayView();
            $inscriptions = $this->inscriptionService->getInscriptions(idClasse: $classe->getIdClasse());
            $nb_filles = 0;
            $nb_garcons = 0;
            foreach ($inscriptions as $inscription) {
                $etudiant = $this->etudiantService->getByMatricule($inscription->getMatEtudiant());
                if($etudiant !== null){
                    if($etudiant->getSexe() === Sexe::F){
                        $nb_filles++;
                    }
                    else{
                        $nb_garcons++;
                    }
                }
            }
            $classeVw["nb_filles"] = $nb_filles;
            $classeVw["nb_garcons"] = $nb_garcons;
            $classeVw["effectif"] = $nb_filles + $nb_garcons;
            $classeVw["inscr_valide"] = $this->isBetween($classe->getDebutInscription(), $classe->getFinInscription(), $todayDate);
            $classesView[] = $classeVw;
        }
        $totalClasses = $this->classeService->getNbClasses($idClasse, $libelle, $idAnneeScolaire, $niveau);
        $_SESSION["classesView"] = $classesView;
        $_SESSION["totalClasses"] = $totalClasses;
        $_SESSION["annees"] = $this->anneeScolaireService->getAnnees();
        $this->render("classe/list.html.php", "inscription/list.css", "inscription/list.js");
    }

    private function showForm(): void
    {
        $this->render("classe/form.html.php", "classe/form.css", "classe/form.js");
    }

    /**
     * @throws DateMalformedStringException
     */
    private function postForm(): void
    {
        extract($_POST);
        $niveau = Niveau::tryFrom($niveau);
        $filiere = Filiere::tryFrom($filiere);
        $this->validator->isEmpty("libelleEmpty", $libelle, "Le libellé est obligatoire");
        $todayDate = new DateTime();
        $todayDate->setTime(0, 0, 0);
        $debutInscription = new DateTime($debut_inscription);
        $finInscription = new DateTime($fin_inscription);
        if($todayDate > $debutInscription){
            $this->validator->addError("debutInscExpired", "La date du debut minimun >= [today:" . $todayDate->format("Y-m-d") . "]");
        }
        if ($finInscription < $debutInscription) {
            $this->validator->addError("finInscSupDebut", "La fin de l'inscription doit être supérieure au début");
        }
        if($this->validator->isValid()){
            $classe = new Classe(
                idClasse: null,
                libelle: $libelle,
                niveau: $niveau,
                filiere: $filiere,
                debutInscription: $debutInscription,
                finInscription: $finInscription,
                idAnneeScolaire: null
            );
            $this->classeService->enregistrerClasse($classe, $this->validator);
        }
        $this->notification("La classe a bien été créée", "Une erreur est survenue");
        if($this->validator->isValid()){
            unset($_POST["niveau"], $_POST["filiere"], $_POST["debutInscription"], $_POST["finInscription"]);
            $this->showList();
        }
        $this->showForm();
    }


}