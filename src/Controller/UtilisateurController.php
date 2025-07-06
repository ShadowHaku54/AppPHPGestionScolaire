<?php

namespace App\Controller;

use App\Config\Controller;
use App\EnumDomain\Role;
use App\Service\EtudiantService;
use App\Service\UtilisateurService;

class UtilisateurController extends Controller
{
    private UtilisateurService $utilisateurService;
    private EtudiantService $etudiantService;

    public function __construct()
    {
        parent::__construct();
        $this->utilisateurService = new UtilisateurService();
        $this->etudiantService = new etudiantService();
        $this->handleRequest();
    }

    protected function handleRequest(): void
    {
        $action = $_REQUEST["action"] ?? 'form-login';
        switch ($action) {
            case 'form-login':
                $this->showForm();
                break;
            case 'logout':
                $this->logout();
                break;
            case 'login':
                $this->login();
                break;
            case 'show-home':
                $this->showHome();
                break;
            default:
                # code...
                break;
        }
    }


    private function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
        $this->showForm();
    }


    private function login(): void
    {
        extract($_POST);
        $role = $this->validator->isEmailOfSchool($emailOfSchool, "emailOfSchool", "L'email de l'école n'est pas valide");
        if ($this->validator->isValid()) {
            if ($role === Role::ETU) {
                $utilisateur = $this->etudiantService->seConnecter($emailOfSchool, $password);
            } else {
                $utilisateur = $this->utilisateurService->seConnecter($emailOfSchool, $password);
            }
            $this->validator->isNull("userNotfound", $utilisateur, "Email ou mot de passe incorrect");
            if ($this->validator->isValid()) {
                $_SESSION["utilisateur"] = $utilisateur;
            }
        }
        $this->notification(
            "Bienvenue sur la page d'accueil",
            "Erreur de connexion",
        );
        if ($this->validator->isValid()) {
            $this->showHome();
        }else{
            $this->showForm();
        }
    }

    private function showForm(): void
    {
        include_once "../view/utilisateur/login.html.php";
    }

    private function showHome(): void
    {
        $this->render("utilisateur/home.html.php");
    }
}