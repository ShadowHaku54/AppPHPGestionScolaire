<?php

namespace App\Controller;

use App\Config\Controller;
use App\Model\Module;
use App\Service\ModuleService;

class ModuleController extends Controller
{
    private ModuleService $moduleService;

    public function __construct()
    {
        parent::__construct();
        $this->moduleService = new ModuleService();
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

    private function showList()
    {
        extract($_POST);
        $idModule = null;
        $nomModule = null;
        $limit = isset($per_page) ? $this->validator->isInt($per_page) : 10;
        $offset = 0;
        if (isset($page) && $this->validator->isInt($page) !== null) {
            $offset = ((int)$page - 1) * $limit;
        }
        if (!(empty($search))) {
            $idModule = $this->validator->isInt($search);
            if ($idModule === null) {
                $nomModule = $search;
            }
        }

        $modulesView = [];
        $modules = $this->moduleService->getModules($idModule, $nomModule, $offset, $limit);
        foreach ($modules as $classe) {
            $classeVw = $classe->toArrayView();
            $modulesView[] = $classeVw;
        }
        $totalModules = $this->moduleService->getNbModules($idModule, $nomModule);
        $_SESSION["modulesView"] = $modulesView;
        $_SESSION["totalModules"] = $totalModules;
        $this->render("module/list.html.php", "inscription/list.css", "inscription/script.js");
    }

    private function showForm(): void
    {
        $this->render("module/form.html.php", "module/form.css", "module/form.js");
    }

    private function postForm(): void
    {
        extract($_POST);
        $this->validator->isEmpty("nomModuleEmpty", $nom, "Le nom est obligatoire");
        $this->validator->isEmpty("nbHeureEmpty", $nb_heure, "Le nombre d'heure est obligatoire");
        $nbHeure = $this->validator->isInt($nb_heure);
        if($this->validator->isValid()){
            $module = new Module(null, $nom, $nbHeure);
            $this->moduleService->ajouterModule($module, $this->validator);
        }
        $this->notification("Module bien créer", "Une erreur est survenue");
        if($this->validator->isValid()){
            $this->showList();
        }
        $this->showForm();
    }
}