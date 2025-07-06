<?php
declare(strict_types=1);

namespace App\Repository;

use App\Config\Model;
use App\Config\Repository;
use App\Model\Module;

class ModuleRepository extends Repository
{
    protected const COLUMNS = 'nom, nb_heure';
    protected const VALUES = ':nom, :nb_heure';
    protected const SELECT_COLUMNS = ['id_module', 'nom', 'nb_heure'];
    protected string $table = 'module';
    protected string $nameID = 'id_module';
    protected string $modelClass = Module::class;

    /** @param Module $obj */
    public function insert(Model $obj): bool
    {
        return parent::insert($obj);
    }

    /**
     * @param array $filters
     * @param int $offset
     * @param int|null $limit
     * @param string $order
     * @return Module[]
     */
    public function select(array $filters = [], int $offset = 0, int|null $limit = null, string $order = 'DESC'): array
    {
        return parent::select($filters, $offset, $limit);
    }


    /**
     * @param array<string, mixed> $filters
     * @return Module|null
     */
    public function selectUnique(array $filters = []): Model|null
    {
        return parent::selectUnique($filters);
    }

    /**
     * @return Module|null
     */
    public function selectLast(): Model|null
    {
        return parent::selectLast();
    }
}
