<?php
declare(strict_types=1);

namespace App\Repository;

use App\Config\Model;
use App\Config\Repository;
use App\Model\AnneeScolaire;

class AnneeScolaireRepository extends Repository
{
    protected const COLUMNS = 'debut, fin, date_definition';
    protected const VALUES = ':debut, :fin, :date_definition';
    protected const SELECT_COLUMNS = ['id_annee_scolaire', 'debut', 'fin', 'date_definition'];
    protected string $table = 'annee_scolaire';
    protected string $nameID = 'id_annee_scolaire';
    protected string $modelClass = AnneeScolaire::class;

    /** @param AnneeScolaire $obj */
    public function insert(Model $obj): bool
    {
        return parent::insert($obj);
    }

    /**
     * @param array $filters
     * @param int $offset
     * @param int|null $limit
     * @param string $order
     * @return AnneeScolaire[]
     */
    public function select(array $filters = [], int $offset = 0, int|null $limit = null, string $order = 'DESC'): array
    {
        return parent::select($filters, $offset, $limit);
    }


    /**
     * @param array<string, mixed> $filters
     * @return AnneeScolaire|null
     */
    public function selectUnique(array $filters = []): Model|null
    {
        return parent::selectUnique($filters);
    }

    /**
     * @return AnneeScolaire|null
     */
    public function selectLast(): Model|null
    {
        return parent::selectLast();
    }
}
