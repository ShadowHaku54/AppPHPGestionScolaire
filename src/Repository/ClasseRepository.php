<?php
declare(strict_types=1);

namespace App\Repository;

use App\Config\Model;
use App\Config\Repository;
use App\Model\Classe;

class ClasseRepository extends Repository
{
    protected const COLUMNS = 'libelle, niveau, filiere, debut_inscription, fin_inscription, id_annee_scolaire';
    protected const VALUES = ':libelle, :niveau, :filiere, :debut_inscription, :fin_inscription, :id_annee_scolaire';
    protected const SELECT_COLUMNS = [
        'id_classe', 'libelle', 'niveau', 'filiere',
        'debut_inscription', 'fin_inscription', 'id_annee_scolaire'
    ];
    protected string $table = 'classe';
    protected string $nameID = 'id_classe';
    protected string $modelClass = Classe::class;


    /** @param Classe $obj */
    public function insert(Model $obj): bool
    {
        return parent::insert($obj);
    }

    /**
     * @param array $filters
     * @param int $offset
     * @param int|null $limit
     * @param string $order
     * @return Classe[]
     */
    public function select(array $filters = [], int $offset = 0, int|null $limit = null, string $order = 'DESC'): array
    {
        return parent::select($filters, $offset, $limit);
    }


    /**
     * @param array<string, mixed> $filters
     * @return Classe|null
     */
    public function selectUnique(array $filters = []): Model|null
    {
        return parent::selectUnique($filters);
    }

    /**
     * @return Classe|null
     */
    public function selectLast(): Model|null
    {
        return parent::selectLast();
    }
}
