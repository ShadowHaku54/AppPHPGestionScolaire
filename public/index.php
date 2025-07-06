<?php

namespace App\public;


use App\Controller\AnneeScolaireController;
use App\Controller\ClasseController;
use App\Controller\DemandeController;
use App\Controller\ErrorController;
use App\Controller\EtudiantController;
use App\Controller\InscriptionController;
use App\Controller\ModuleController;
use App\Controller\ProfesseurController;
use App\Controller\UtilisateurController;


include_once "../vendor/autoload.php";


if (session_status()==PHP_SESSION_NONE) {
    session_start();
}


$controlleName = $_REQUEST["controller"]??'utilisateur';


$controller = match ($controlleName) {
    'utilisateur' => new UtilisateurController(),
    'etudiant' => new EtudiantController(),
    'classe' => new ClasseController(),
    'module' => new ModuleController(),
    'professeur' => new ProfesseurController(),
    'demande' => new DemandeController(),
    'annee_scolaire' => new AnneeScolaireController(),
    'inscription' => new InscriptionController(),
    default => new ErrorController(),
};
