<?php

namespace App\Controller;

use App\Config\Controller;
use App\EnumDomain\GradeProfesseur;
use App\Model\Professeur;
use App\Service\ProfesseurService;

class ProfesseurController extends Controller
{
    private ProfesseurService $professeurService;

    public function __construct()
    {
        parent::__construct();
        $this->professeurService = new ProfesseurService();
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
        extract($_POST);
        $idProfesseur = null;
        $nomProf = null;
        $grade = isset($grade) ? GradeProfesseur::tryFrom($grade) : null;
        $limit = isset($per_page) ? $this->validator->isInt($per_page) : 10;
        $offset = 0;
        if (isset($page) && $this->validator->isInt($page) !== null) {
            $offset = ((int)$page - 1) * $limit;
        }
        if (!(empty($search))) {
            $idProfesseur = $this->validator->isInt($search);
            if ($idProfesseur === null) {
                $nomProf = $search;
            }
        }

        $professeur = $this->professeurService->getProfs($idProfesseur, $nomProf, $grade, $offset, $limit);
        $professeursView = [];
        foreach ($professeur as $prof) {
            $profVw = $prof->toArrayView();
            $professeursView[] = $profVw;
        }
        $totalProfesseurs = $this->professeurService->getNbProfs($idProfesseur, $nomProf, $grade);
        $_SESSION["professeursView"] = $professeursView;
        $_SESSION["totalProfesseurs"] = $totalProfesseurs;
        $this->render("prof/list.html.php", "inscription/list.css", "inscription/list.js");
    }

    private function showForm()
    {
        $this->render("prof/form.html.php", "prof/form.css", "prof/form.js");
    }

    private function postForm(): void
    {
        extract($_POST);
        $this->validator->isEmpty("nomEmpty", $nom, "Le nom est obligatoire");
        $this->validator->isEmpty("prenomEmpty", $prenom, "Le prénom est obligatoire");
        $grade = GradeProfesseur::tryFrom($grade) ?? GradeProfesseur::AUTRE;
        if ($this->validator->isValid()) {
            $prof = new Professeur(
                null,
                $nom,
                $prenom,
                $grade
            );
            $this->professeurService->ajouterProf($prof, $this->validator);
        }
        $this->notification("Prof enregistré", "Une erreur est survenue");
        if($this->validator->isValid()){
            unset($_POST["nom"], $_POST["prenom"], $_POST["grade"]);
            $this->showList();
        }
        $this->showForm();
    }


}