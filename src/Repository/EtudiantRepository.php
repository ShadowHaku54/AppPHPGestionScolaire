<?php
declare(strict_types=1);

namespace App\Repository;

use App\Config\Model;
use App\Config\Repository;
use App\EnumDomain\Role;
use App\Model\Etudiant;

class EtudiantRepository extends Repository
{
    protected const COLUMNS = 'nom, prenom, email_of_school, password, role, mat_etudiant, personal_email, adresse, sexe';
    // ✅ corrigé
    protected const VALUES = ':nom, :prenom, :email_of_school, :password, :role,
                          :mat_etudiant, :personal_email, :adresse, :sexe';

    protected const SELECT_COLUMNS = [
        'id_utilisateur', 'nom', 'prenom', 'email_of_school', 'password', 'role',
        'mat_etudiant', 'personal_email', 'adresse', 'sexe'
    ];

    protected string $table = 'utilisateur';
    protected string $nameID = 'id_utilisateur';
    protected string $modelClass = Etudiant::class;

    /** @param Etudiant $obj */
    public function insert(Model $obj): bool
    {
        return parent::insert($obj);
    }

    /**
     * @param array $filters
     * @param int $offset
     * @param int|null $limit
     * @param string $order
     * @return Etudiant[]
     */
    public function select(array $filters = [], int $offset = 0, int|null $limit = null, string $order = 'DESC'): array
    {
        $filters["role"] = Role::ETU->value;
        return parent::select($filters, $offset, $limit);
    }


    /**
     * @param array<string, mixed> $filters
     * @return Etudiant|null
     */
    public function selectUnique(array $filters = []): Model|null
    {
        $filters["role"] = Role::ETU->value;
        return parent::selectUnique($filters);
    }

    /**
     * @return Etudiant|null
     */
    public function selectLast(): Model|null
    {
        return parent::selectLast();
    }

    /**
     * @param array $filters
     * @return int
     */
    public function count(array $filters = []): int
    {
        $filters["role"] = Role::ETU->value;
        return parent::count($filters);
    }


}
