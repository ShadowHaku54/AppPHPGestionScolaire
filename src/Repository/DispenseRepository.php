<?php
declare(strict_types=1);

namespace App\Repository;

use App\Config\Model;
use App\Config\Repository;
use App\Model\Dispense;

class DispenseRepository extends Repository
{
    protected const COLUMNS = 'id_professeur, id_module';
    protected const VALUES = ':id_professeur, :id_module';
    protected const SELECT_COLUMNS = ['id_dispense', 'id_professeur', 'id_module'];
    protected string $table = 'Dispense';
    protected string $nameID = 'id_dispense';
    protected string $modelClass = Dispense::class;


    /** @param Dispense $obj */
    public function insert(Model $obj): bool
    {
        return parent::insert($obj);
    }

    /**
     * @param array $filters
     * @param int $offset
     * @param int|null $limit
     * @param string $order
     * @return Dispense[]
     */
    public function select(array $filters = [], int $offset = 0, int|null $limit = null, string $order = 'DESC'): array
    {
        return parent::select($filters, $offset, $limit);
    }


    /**
     * @param array<string, mixed> $filters
     * @return Dispense|null
     */
    public function selectUnique(array $filters = []): Model|null
    {
        return parent::selectUnique($filters);
    }

    /**
     * @return Dispense|null
     */
    public function selectLast(): Model|null
    {
        return parent::selectLast();
    }
}
