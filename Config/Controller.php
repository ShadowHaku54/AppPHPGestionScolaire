<?php

namespace App\Config;

use App\Controller\UtilisateurController;
use DateTime;

abstract class Controller
{
    protected Validator $validator;

    public function __construct()
    {
        $this->validator = new Validator();
        $this->checkConnexion();
    }

    // Si l'utilisateur est déconnecter => aller au login
    private function checkConnexion(): void
    {
        if (!$this instanceof UtilisateurController && !isset($_SESSION["utilisateur"])) {
            include_once "../view/utilisateur/login.html.php";
            die();
        }
    }

    protected function render(string $pathHTML, string $pathCSS = "", string $pathJS = ""): void
    {
        require_once "../view/layout/header.html.php";
        require_once "../view/layout/notification.html.php";
        require_once "../view/$pathHTML";
        require_once "../view/layout/footer.html.php";
        die();
    }


    public function notification(string $succesMessage, string $errorMessage): void
    {
        if ($_SESSION["notification"]["active"] ?? false) {
            return;
        }
        $_SESSION["notification"]["active"] = true;
        if ($this->validator->isValid()) {
            $_SESSION["notification"]["type"] = "success";
            $_SESSION["notification"]["message"] = $succesMessage;
        } else {
            $_SESSION["notification"]["type"] = "error";
            $_SESSION["notification"]["message"] = $errorMessage;
            $_SESSION["errors"] = $this->validator->getErrors();
        }
    }

    public function error(string $errorMessage): void
    {
        $_SESSION["notification"]["active"] = true;
        $_SESSION["notification"]["type"] = "error";
        $_SESSION["notification"]["message"] = $errorMessage;
    }

    public function success(string $successMessage): void
    {
        $_SESSION["notification"]["active"] = true;
        $_SESSION["notification"]["type"] = "success";
        $_SESSION["notification"]["message"] = $successMessage;
    }

    public function isBetween(DateTime $debut, DateTime $fin, DateTime $date): bool
    {
        return $date >= $debut && $date <= $fin;
    }

}