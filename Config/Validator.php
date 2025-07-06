<?php

namespace App\Config;

use App\EnumDomain\Role;
use ValueError;

class Validator
{
    private array $errors = [];

    /**
     * Get the value of errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }

    public function addError(string $name, string $error): void
    {
        $this->errors[$name] = $error;
    }

    public function isEmpty(string $name, string $value, string $sms): void
    {
        if (empty($value)) {
            $this->errors[$name] = $sms;
        }
    }

    public function isNull(string $name, $value, string $sms): void
    {
        if ($value === null) {
            $this->errors[$name] = $sms;
        }
    }

    function isInt($chaine) {
        $res = filter_var($chaine, FILTER_VALIDATE_INT);
        if ($res === false) {
            return null;
        }
        return $res;
    }

    function isMatriculeValid(string $matricule) {
        $res = preg_match('/^ISM-\d{4}\/DK-[1-9]\d*$/', $matricule);
        if ($res === 0) {
            return null;
        }
        return $matricule;
    }



    public function isEmail(string $name, string $value, string $sms): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$name] = $sms;
        }
    }

    /**
     * Vérifie si un email correspond au format de l'école.
     * Si oui, retourne le rôle. Sinon, retourne une chaîne vide.
     *
     * @param string $emailOfSchool
     * @return string
     */
    public function isEmailOfSchoolA(string $name, string $emailOfSchool, string $message): string
    {
        $er = false;
        $pattern = '/^[a-z0-9]+(?:-[a-z0-9]+)*(?:-\d+)?@ism\.([a-z]+)\.sn$/';

        if (preg_match($pattern, strtolower($emailOfSchool), $matches)) {
            $role = strtoupper($matches[1]);
            try {
                $roleR = Role::fromName($role);
                return $role;
            } catch (ValueError $e) {
                $er = true;
            }
        }

        $this->errors[$name] = $message;
        return '';
    }

    /**
     * Analyse un email d’école et renvoie le rôle détecté ou '' si l’email est invalide.
     *
     * Exemples :
     *   'fatimata-zongo@ism.etu.sn'   → 'ETU'
     *   'abdoulaye-diallo@ism.rp.sn'  → 'RP'
     *   'adresse.invalide@exemple.sn' → ''
     *
     * @param string $name
     * @param string $email
     * @param string $message
     * @return string  Rôle valide ('RP', 'AC', 'ETU') ou chaîne vide si erreur.
     */
    function isEmailOfSchool(string $email, string $name = null, string $message = null): Role|null
    {
        // 1. Validation RFC minimale
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$name] = $message;
            return null;
        }

        // 2. Séparation locale / domaine
        [$localPart, $domain] = explode('@', $email, 2);

        // 3. Domaine doit être ism.<role>.sn
        if (!preg_match('/^ism\.([a-z]{2,3})\.sn$/i', $domain, $matches)) {
            $this->errors[$name] = $message;
            return null;
        }
        $roleStr = strtoupper($matches[1]);

        // 4. Vérification via l’énum
        $roleEnum = Role::fromName($roleStr);
        if (!$roleEnum) {
            $this->errors[$name] = $message;
            return null;
        }

        // 5. Partie locale au format prenom-nom[-n]
        if (!preg_match('/^[a-z0-9]+-[a-z0-9]+(?:-\d+)?$/', $localPart)) {
            $this->errors[$name] = $message;
            return null;
        }

        // ✅ Tout est bon : on renvoie la value exacte de l’énum (ex. 'ETU')
        return $roleEnum;
    }
}