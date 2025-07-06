<?php

namespace App\Controller;

use App\Config\Controller;
use App\EnumDomain\Sexe;
use App\Service\AnneeScolaireService;
use App\Service\EtudiantService;

class EtudiantController extends Controller
{
    private EtudiantService $etudiantService;
    private AnneeScolaireService $anneeScolaireService;

    public function __construct()
    {
        parent::__construct();
        $this->etudiantService = new etudiantService();
        $this->anneeScolaireService = new AnneeScolaireService();
        $this->handleRequest();
    }

    protected function handleRequest(): void
    {
        $action = $_REQUEST["action"] ?? 'show_list';
        switch ($action) {
            case 'show_list':
                $this->show_list();
                break;
            case 'select_etu':
                $this->selectEtu();
                break;
            default:
                $controller = new ErrorController();
                break;
        }
    }

    private function show_list(): void
    {
        extract($_POST);
        $matEtudiant = null;
        $email = null;
        $sexe = isset($sexe) ? Sexe::tryFrom($sexe) : null;
        $limit = isset($per_page) ? $this->validator->isInt($per_page) : 10;
        $offset = 0;
        if (isset($page) && $this->validator->isInt($page) !== null) {
            $offset = ((int)$page - 1) * $limit;
        }
        if (!(empty($search))) {
            $matEtudiant = $this->validator->isMatriculeValid($search);
            $email = $this->validator->isEmailOfSchool($search) === null ? null : $search;
            if ($matEtudiant === null && $email === null) {
                $this->error("Mauvaise valeur de recherche");
            }
        }

        $etudiants = $this->etudiantService->getEtudiants($matEtudiant, $sexe, $email, $offset, $limit);
        $etudiantsView = [];
        foreach ($etudiants as $etudiant) {
            $etudiantsView[] = $etudiant->toArrayView();
        }
        $totalEtudiants = $this->etudiantService->getNbEtudiants($matEtudiant, $sexe, $email);
        $_SESSION["etudiantsView"] = $etudiantsView;
        $_SESSION["totalEtudiants"] = $totalEtudiants;
        $this->render('etudiant/list.html.php', 'inscription/list.css', 'inscription/list.js');
    }


}