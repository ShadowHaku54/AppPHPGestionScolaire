<?php
declare(strict_types=1);

namespace App\Repository;

use App\Config\Model;
use App\Config\Repository;
use App\Model\Inscription;

class InscriptionRepository extends Repository
{
    protected const COLUMNS = 'date_inscription, id_annee_scolaire, mat_etudiant, id_classe, statut, type';
    protected const VALUES = ':date_inscription, :id_annee_scolaire, :mat_etudiant, :id_classe, :statut, :type';
    protected const SELECT_COLUMNS = [
        'id_inscription', 'date_inscription', 'id_annee_scolaire',
        'mat_etudiant', 'id_classe', 'statut', 'type'
    ];
    protected string $table = 'inscription';
    protected string $nameID = 'id_inscription';
    protected string $modelClass = Inscription::class;

    /** @param Inscription $obj */
    public function insert(Model $obj): bool
    {
        return parent::insert($obj);
    }

    /**
     * @param array $filters
     * @param int $offset
     * @param int|null $limit
     * @param string $order
     * @return Inscription[]
     */
    public function select(array $filters = [], int $offset = 0, int|null $limit = null, string $order = 'DESC'): array
    {
        return parent::select($filters, $offset, $limit);
    }


    /**
     * @param array<string, mixed> $filters
     * @return Inscription|null
     */
    public function selectUnique(array $filters = []): Model|null
    {
        return parent::selectUnique($filters);
    }

    /**
     * @return Inscription|null
     */
    public function selectLast(): Model|null
    {
        return parent::selectLast();
    }
}
