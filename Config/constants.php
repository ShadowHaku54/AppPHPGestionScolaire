<?php


use App\EnumDomain\Role;

//const APP_ROOT = "http://www.salim-ouedraogo.ism.edu.sn:8080/ism.sn";
//const APP_NAME = "ISM";

define("ONGLETS", [
    'dashboard' => [
        'label' => 'Dashboard',
        'href' => '?controller=utilisateur&action=show-home',
        'icon' => 'fa-house',
        'users' => Role::cases(),
    ],
    'inscription' => [
        'label' => 'Inscriptions',
        'href' => '?controller=inscription',
        'icon' => 'fa-list-ul',
        'users' => [Role::ETU, Role::AC],
    ],
    'etudiants' => [
        'label' => 'Étudiants',
        'href' => '?controller=etudiant',
        'icon' => 'fa-user-graduate',
        'users' => [Role::AC],
    ],
    'classes' => [
        'label' => 'Classes',
        'href' => '?controller=classe',
        'icon' => 'fa-chalkboard',
        'users' => [Role::RP, Role::AC],
    ],
    'modules' => [
        'label' => 'Modules',
        'href' => '?controller=module',
        'icon' => 'fa-book-open',
        'users' => [Role::RP],
    ],
    'professeurs' => [
        'label' => 'Professeurs',
        'href' => '?controller=professeur',
        'icon' => 'fa-user-tie',
        'users' => [Role::RP],
    ],
    'demande' => [
        'label' => 'Demandes',
        'href' => '?controller=demande',
        'icon' => 'fa-folder',
        'users' => Role::cases(),
    ],
    'annee_scolaire' => [
        'label' => 'Années scolaire',
        'href' => '?controller=annee_scolaire',
        'icon' => 'fa-calendar-days',
        'users' => [Role::RP, Role::AC],
    ],
]);


