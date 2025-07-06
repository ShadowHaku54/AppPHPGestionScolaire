<?php
declare(strict_types=1);

namespace App\Repository;

use App\Config\Model;
use App\Config\Repository;
use App\Model\Affectation;

class AffectationRepository extends Repository
{
    protected const COLUMNS = 'id_professeur, id_classe';
    protected const VALUES = ':id_professeur, :id_classe';
    protected const SELECT_COLUMNS = ['id_affectation', 'id_professeur', 'id_classe'];
    protected string $table = 'affectation';
    protected string $nameID = 'id_affectation';
    protected string $modelClass = Affectation::class;

    /** @param Affectation $obj */
    public function insert(Model $obj): bool
    {
        return parent::insert($obj);
    }

    /**
     * @param array $filters
     * @param int $offset
     * @param int|null $limit
     * @param string $order
     * @return Affectation[]
     */
    public function select(array $filters = [], int $offset = 0, int|null $limit = null, string $order = 'DESC'): array
    {
        return parent::select($filters, $offset, $limit);
    }


    /**
     * @param array<string, mixed> $filters
     * @return Affectation|null
     */
    public function selectUnique(array $filters = []): Model|null
    {
        return parent::selectUnique($filters);
    }

    /**
     * @param string $order
     * @return Affectation|null
     */
    public function selectLast(): Model|null
    {
        return parent::selectLast();
    }
}
