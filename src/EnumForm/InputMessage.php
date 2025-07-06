<?php

namespace App\EnumForm;

enum InputMessage
{
    case ERROR_TECH_ANNEE_DEBUT_EMPTY;
    case ERROR_TECH_ANNEE_DEBUT_NEGATIVE;
    case ERROR_TECH_ANNEE_FIN_INCOHERENT;


    case ERROR_METIER_ANNEE_INVALIDE;
    case ERROR_METIER_DISPENSE_INVALIDE;
    case ERROR_METIER_CLASSE_INVALIDE;
    case ERROR_METIER_MODULE_INVALIDE;
    case ERROR_METIER_UTILISATEUR_INVALIDE;
    case ERROR_METIER_ETUDIANT_INVALIDE;
    case ERROR_METIER_AFFECTATION_INVALIDE;
    case ERROR_METIER_DEMANDE_INVALIDE;
    case ERROR_METIER_INSCRIPTION_INVALIDE;

    case ERROR_METIER_ANNEE_NOT_EXIST;
    case ERROR_METIER_PROF_NOT_EXIST;
    case ERROR_METIER_MODULE_NOT_EXIST;
    case ERROR_METIER_CLASSE_NOT_EXIST;
    case ERROR_METIER_INSCRIPTION_NOT_EXIST;
    case ERROR_METIER_ETUDIANT_NOT_EXIST;


    case ERROR_FINALE_ANNEE_NOT_CREATED;
    case ERROR_FINALE_PROF_NOT_CREATED;
    case ERROR_FINALE_DISPENSE_NOT_CREATED;
    case ERROR_FINALE_MODULE_NOT_CREATED;
    case ERROR_FINALE_CLASSE_NOT_CREATED;
    case ERROR_FINALE_UTILISATEUR_NOT_CREATED;
    case ERROR_FINALE_ETUDIANT_NOT_CREATED;
    case ERROR_FINALE_AFFECTATION_NOT_CREATED;
    case ERROR_FINALE_INSCRIPTION_NOT_CREATED;

    case SUCCESS_FINALE_ANNEE_CREATED;
    case SUCCESS_FINALE_PROF_CREATED;
    case SUCCESS_FINALE_DISPENSE_CREATED;
    case SUCCESS_FINALE_MODULE_CREATED;
    case SUCCESS_FINALE_CLASSE_CREATED;
    case SUCCESS_FINALE_UTILISATEUR_CREATED;
    case SUCCESS_FINALE_ETUDIANT_CREATED;
    case SUCCESS_FINALE_AFFECTATION_CREATED;
    case SUCCESS_FINALE_INSCRIPTION_CREATED;

    private const NOT_EXISTED = " n'existe pas";

    public function message(): string
    {
        return match ($this) {
            self::ERROR_TECH_ANNEE_DEBUT_EMPTY => "Ce champ est requis. Veuillez remplir le champ.",
            self::ERROR_TECH_ANNEE_DEBUT_NEGATIVE => "Entrer un entier possitif ici",
            self::ERROR_TECH_ANNEE_FIN_INCOHERENT => "La fin doit être le suivant du debut.",

            self::ERROR_METIER_ANNEE_INVALIDE => "Une année scolaire avec ce début existe déjà.",
            self::ERROR_METIER_MODULE_INVALIDE => "Un module avec le même nom existe déja",
            self::ERROR_METIER_CLASSE_INVALIDE => "Une classe a le même libellé pour cette année scolaire",
            self::ERROR_METIER_DISPENSE_INVALIDE => "Cet module est déjà dispensé par ce prof",
            self::ERROR_METIER_ETUDIANT_INVALIDE => "Un étudiant a le même email d'école",
            self::ERROR_METIER_UTILISATEUR_INVALIDE => "Cet utilisateur existe déjà par son id",
            self::ERROR_METIER_AFFECTATION_INVALIDE => "Ce prof est déjà affecter à cette classe",
            self::ERROR_METIER_DEMANDE_INVALIDE => "Impossible d'éffectuer une demande sur une ancienne inscription",
            self::ERROR_METIER_INSCRIPTION_INVALIDE => "Cet étudiant est déjà inscrit au cours de cette année scolaire",

            self::ERROR_METIER_ANNEE_NOT_EXIST => $this->makeMessage("cette année scolaire", "not-existed"),
            self::ERROR_METIER_PROF_NOT_EXIST => $this->makeMessage("ce prof", "not-existed"),
            self::ERROR_METIER_MODULE_NOT_EXIST => $this->makeMessage("ce module", "not-existed"),
            self::ERROR_METIER_INSCRIPTION_NOT_EXIST => $this->makeMessage("cette inscription", "not-existed"),
            self::ERROR_METIER_CLASSE_NOT_EXIST => $this->makeMessage("cette classe", "not-existed"),
            self::ERROR_METIER_ETUDIANT_NOT_EXIST => $this->makeMessage("cet étudiant", "not-existed"),

            self::ERROR_FINALE_ANNEE_NOT_CREATED => $this->makeMessage("l'année scolaire"),
            self::ERROR_FINALE_PROF_NOT_CREATED => $this->makeMessage("le prof"),
            self::ERROR_FINALE_DISPENSE_NOT_CREATED => $this->makeMessage("la dispense"),
            self::ERROR_FINALE_MODULE_NOT_CREATED => $this->makeMessage("le module"),
            self::ERROR_FINALE_CLASSE_NOT_CREATED => $this->makeMessage("la classe"),
            self::ERROR_FINALE_UTILISATEUR_NOT_CREATED => $this->makeMessage("l'utilisateur"),
            self::ERROR_FINALE_ETUDIANT_NOT_CREATED => $this->makeMessage("l'étudiant"),
            self::ERROR_FINALE_AFFECTATION_NOT_CREATED => $this->makeMessage("l'affection"),
            self::ERROR_FINALE_INSCRIPTION_NOT_CREATED => $this->makeMessage("l'inscription"),

            self::SUCCESS_FINALE_ANNEE_CREATED => $this->makeMessage("de l'année scolaire", "success"),
            self::SUCCESS_FINALE_PROF_CREATED => $this->makeMessage("du prof", "success"),
            self::SUCCESS_FINALE_DISPENSE_CREATED => $this->makeMessage("de la dispense", "success"),
            self::SUCCESS_FINALE_MODULE_CREATED => $this->makeMessage("du module", "success"),
            self::SUCCESS_FINALE_CLASSE_CREATED => $this->makeMessage("de la classe", "success"),
            self::SUCCESS_FINALE_UTILISATEUR_CREATED => $this->makeMessage("de l'utilisateur", "success"),
            self::SUCCESS_FINALE_ETUDIANT_CREATED => $this->makeMessage("de l'étudiant", "success"),
            self::SUCCESS_FINALE_AFFECTATION_CREATED => $this->makeMessage("de l'affectation", "success"),
            self::SUCCESS_FINALE_INSCRIPTION_CREATED => $this->makeMessage("de l'inscription", "success"),
        };
    }

    private function makeMessage(string $label = "", string $type = "error"): string
    {
        if ($type === "error") {
            return ucfirst("échec de l'enregistrement $label.");
        } elseif ($type === "success") {
            return ucfirst("$label enregistré avec succès.");
        } elseif ($type === "not-existed") {
            return ucfirst("$label n'existe pas.");
        } else {
            return ucfirst($label);
        }
    }

    public function isFinale(): bool
    {
        return str_contains($this->name, "FINALE");
    }

    public function typeMessage(): string
    {
        return str_starts_with($this->name, 'ERROR') ? 'error' : 'success';
    }

    public function typeError(): string
    {
        return str_starts_with($this->name, 'ERROR_TECH') ? 'error-technique' : 'error-metier';
    }
}