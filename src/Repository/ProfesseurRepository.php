<?php
declare(strict_types=1);

namespace App\Repository;

use App\Config\Model;
use App\Config\Repository;
use App\Model\Professeur;

class ProfesseurRepository extends Repository
{
    protected const COLUMNS = 'nom, prenom, grade';
    protected const VALUES = ':nom, :prenom, :grade';
    protected const SELECT_COLUMNS = ['id_professeur', 'nom', 'prenom', 'grade'];
    protected string $table = 'professeur';
    protected string $nameID = 'id_professeur';
    protected string $modelClass = Professeur::class;

    /** @param Professeur $obj */
    public function insert(Model $obj): bool
    {
        return parent::insert($obj);
    }

    /**
     * @param array $filters
     * @param int $offset
     * @param int|null $limit
     * @param string $order
     * @return Professeur[]
     */
    public function select(array $filters = [], int $offset = 0, int|null $limit = null, string $order = 'DESC'): array
    {
        return parent::select($filters, $offset, $limit);
    }


    /**
     * @param array<string, mixed> $filters
     * @return Professeur|null
     */
    public function selectUnique(array $filters = []): Model|null
    {
        return parent::selectUnique($filters);
    }

    /**
     * @return Professeur|null
     */
    public function selectLast(): Model|null
    {
        return parent::selectLast();
    }

}
