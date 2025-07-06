<?php
declare(strict_types=1);

namespace App\Repository;

use App\Config\Model;
use App\Config\Repository;
use App\Model\Demande;

class DemandeRepository extends Repository
{
    protected const COLUMNS = 'date_demande, id_annee_scolaire, mat_etudiant, id_inscription, type, motif, statut';
    protected const VALUES = ':date_demande, :id_annee_scolaire, :mat_etudiant, :id_inscription, :type, :motif, :statut';
    protected const SELECT_COLUMNS = [
        'id_demande', 'date_demande', 'id_annee_scolaire', 'mat_etudiant',
        'id_inscription', 'type', 'motif', 'statut'
    ];
    protected string $table = 'demande';
    protected string $nameID = 'id_demande';
    protected string $modelClass = Demande::class;


    /** @param Demande $obj */
    public function insert(Model $obj): bool
    {
        return parent::insert($obj);
    }

    /**
     * @param array $filters
     * @param int $offset
     * @param int|null $limit
     * @param string $order
     * @return Demande[]
     */
    public function select(array $filters = [], int $offset = 0, int|null $limit = null, string $order = 'DESC'): array
    {
        return parent::select($filters, $offset, $limit);
    }


    /**
     * @param array<string, mixed> $filters
     * @return Demande|null
     */
    public function selectUnique(array $filters = []): Model|null
    {
        return parent::selectUnique($filters);
    }

    /**
     * @return Demande|null
     */
    public function selectLast(): Model|null
    {
        return parent::selectLast();
    }
}
