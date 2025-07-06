<?php
declare(strict_types=1);

namespace App\Repository;

use App\Config\Model;
use App\Config\Repository;
use App\Model\Utilisateur;

class UtilisateurRepository extends Repository
{
    protected const COLUMNS = 'nom, prenom, email_of_school, password, role';
    protected const VALUES = ':nom, :prenom, :email_of_school, :password, :role';
    protected const SELECT_COLUMNS = ['id_utilisateur', 'nom', 'prenom', 'email_of_school', 'password', 'role'];
    protected string $table = 'utilisateur';
    protected string $nameID = 'id_utilisateur';
    protected string $modelClass = Utilisateur::class;

    /** @param Utilisateur $obj */
    public function insert(Model $obj): bool
    {
        return parent::insert($obj);
    }

    /**
     * @param array $filters
     * @param int $offset
     * @param int|null $limit
     * @param string $order
     * @return Utilisateur[]
     */
    public function select(array $filters = [], int $offset = 0, int|null $limit = null, string $order = 'DESC'): array
    {
        return parent::select($filters, $offset, $limit);
    }


    /**
     * @param array<string, mixed> $filters
     * @return Utilisateur|null
     */
    public function selectUnique(array $filters = []): Model|null
    {
        return parent::selectUnique($filters);
    }

    /**
     * @return Utilisateur|null
     */
    public function selectLast(): Model|null
    {
        return parent::selectLast();
    }

}
