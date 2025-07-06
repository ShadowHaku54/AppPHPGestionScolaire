<?php
declare(strict_types=1);

namespace App\Service;

use App\EnumForm\InputMessage;
use App\Model\Utilisateur;
use App\Repository\UtilisateurRepository;

class UtilisateurService
{
    private UtilisateurRepository $repo;

    public function __construct()
    {
        $this->repo = new UtilisateurRepository();
    }

    private function emailEcoleExiste(string $emailOfSchool): bool
    {
        return $this->repo->selectUnique(['email_of_school' => $emailOfSchool]) !== null;
    }

    /**
     * Génère un email d'école unique sous la forme prenom-nom[-nombre]@ism.role.sn
     *
     * @param string $prenom
     * @param string $nom
     * @param string $role
     * @return string
     */
    public function genererEmailOfSchool(string $prenom, string $nom, string $role): string
    {
        $base = $this->slugify($prenom) . '-' . $this->slugify($nom);
        $domaine = "ism." . strtolower($role) . ".sn";
        $email = "$base@$domaine";
        $i = 1;
        while ($this->emailEcoleExiste($email)) {
            $email = "{$base}-{$i}@$domaine";
            $i++;
        }
        return $email;
    }

    private function slugify(string $string): string
    {
        $string = strtolower($string);
        $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
        $string = preg_replace('/[^a-z0-9]+/', '-', $string);
        return trim($string, '-');
    }

    private function creerAvecEmailAuto(Utilisateur $user): Utilisateur
    {
        $email = $this->genererEmailOfSchool(
            $user->getPrenom(),
            $user->getNom(),
            $user->getRole()->value
        );
        return new Utilisateur(
            nom: $user->getNom(),
            prenom: $user->getPrenom(),
            emailOfSchool: $email,
            password: $user->getPassword(),
            role: $user->getRole()
        );
    }

    public function enregistrerUtilisateur(Utilisateur $utilisateur): InputMessage
    {
        return $this->repo->insert($this->creerAvecEmailAuto($utilisateur))
            ? InputMessage::SUCCESS_FINALE_UTILISATEUR_CREATED
            : InputMessage::ERROR_FINALE_UTILISATEUR_NOT_CREATED;
    }

    public function getByEmailOfSchool(string $emailOfSchool): ?Utilisateur
    {
        return $this->repo->selectUnique(['email_of_school' => $emailOfSchool]);
    }

    public function seConnecter(string $emailOfSchool, string $password): ?Utilisateur
    {
        $user = $this->getByEmailOfSchool($emailOfSchool);
        if ($user !== null && $user->getPassword() === $password) {
            return $user;
        }
        return null;
    }

    /**
     * @param int $offset
     * @param int|null $limit
     * @return Utilisateur[]
     */
    public function getAllUtilisateurs(int $offset = 0, int|null $limit = null): array
    {
        return $this->repo->select([], $offset, $limit);
    }
}
