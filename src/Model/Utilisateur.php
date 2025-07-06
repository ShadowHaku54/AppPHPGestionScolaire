<?php
declare(strict_types=1);

namespace App\Model;

use App\Config\Model;
use App\EnumDomain\Role;

class Utilisateur extends Model
{
    protected string $nom;
    protected string $prenom;
    protected string $emailOfSchool;
    protected string $password;
    protected Role $role;

    public function __construct(string $nom, string $prenom, string $emailOfSchool, string $password, Role $role)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->emailOfSchool = $emailOfSchool;
        $this->password = $password;
        $this->role = $role;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function getEmailOfSchool(): string
    {
        return $this->emailOfSchool;
    }

    public function setEmailOfSchool(string $emailOfSchool): void
    {
        $this->emailOfSchool = $emailOfSchool;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setRole(Role $role): void
    {
        $this->role = $role;
    }

    public function toArray(): array
    {
        return [
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email_of_school' => $this->emailOfSchool,
            'password' => $this->password,
            'role' => $this->role->name
        ];
    }

    public static function fromArray(array $data): Utilisateur
    {
        return new Utilisateur(
            $data['nom'],
            $data['prenom'],
            $data['email_of_school'],
            $data['password'],
            Role::fromName($data['role'])
        );
    }

    public function toArrayView(): array
    {
        return [
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email_of_school' => $this->emailOfSchool,
            'role' => $this->role->value
        ];
    }

    public function format(string $pattern = '{role} {prenom} {nom}'): string
    {
        $replace = [
            '{nom}' => $this->nom,
            '{prenom}' => $this->prenom,
            '{email}' => $this->emailOfSchool,
            '{role}' => $this->role->value,
        ];

        return strtr($pattern, $replace);
    }
}
