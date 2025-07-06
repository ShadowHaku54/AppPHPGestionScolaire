<?php

namespace App\EnumForm;


enum ErrorMetier: string
{

    case ANNEE_INVALID = "Une année scolaire avec ce début existe déjà.";
    case MODULE_INVALID = "Un module avec le même nom existe déja";
    case CLASSE_INVALID = "Une classe a le même libellé pour cette année scolaire";
    case DISPENSE_INVALID = "Cet module est déjà dispensé par ce prof";
    case ETUDIANT_INVALID = "Un étudiant a le même email d'école";
    case UTILISATEUR_INVALID = "Email ou mot de passe incorrect";
    case AFFECTATION_INVALID = "Ce prof est déjà affecter à cette classe";
    case DEMANDE_INVALID = "Impossible d'éffectuer une demande sur une ancienne inscription";
    case INSCRIPTION_INVALID = "Cet étudiant est déjà inscrit au cours de cette année scolaire";

    
    private const NOT_EXISTED = " n'existe pas";
    case ANNEE_NOT_EXIST = "cette année scolaire";
    case PROF_NOT_EXIST = "ce prof";
    case MODULE_NOT_EXIST = "ce module";
    case INSCRIPTION_NOT_EXIST = "cette inscription";
    case CLASSE_NOT_EXIST = "cette classe";
    case ETUDIANT_NOT_EXIST = "cet étudiant";

}