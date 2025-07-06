<?php
declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;

abstract class Repository
{
    protected const COLUMNS = '';
    protected const VALUES = '';
    protected const SELECT_COLUMNS = [];
    protected string $table = '';
    protected string $nameID = '';
    protected string $modelClass = Model::class;

    public function __construct()
    {
        Database::connexion();
    }


    protected function insert(Model $obj): bool
    {
        try {
            $sql = "INSERT INTO {$this->table} (" . static::COLUMNS . ") VALUES (" . static::VALUES . ")";
            return Database::getPdo()->prepare($sql)->execute($obj->toArray());
        } catch (PDOException $e) {
            print $e->getMessage() . "\n";
            return false;
        }
    }


    /**
     * @param array $filters
     * @param int $offset
     * @param int|null $limit
     * @param string $order
     * @return Model[]
     */
    protected function select(array $filters = [],
                              int   $offset = 0,
                              int|null $limit = null,
                              string $order = 'DESC'): array
    {
        try {
            $sql    = "SELECT * FROM {$this->table}";
            $params = [];

            $this->filtered($filters, $params, $sql);

            $order = strtoupper($order);
            if (!in_array($order, ['ASC', 'DESC'])) {
                throw new \InvalidArgumentException("L'ordre doit être 'ASC' ou 'DESC'");
            }
            $sql .= " ORDER BY $this->nameID $order";

            if ($limit !== null) {
                if ($limit < 0 || $offset < 0) {
                    throw new \InvalidArgumentException('offset/limit doivent être >= 0');
                }
                $sql .= ' LIMIT ' . (int)$offset . ', ' . (int)$limit;
            } elseif ($offset > 0) {
                $sql .= ' LIMIT ' . (int)$offset . ', ' . Database::MYSQL_MAX_LIMIT;
            }

            $stmt = Database::getPdo()->prepare($sql);
            $stmt->execute($params);

            $rows = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $rows[] = ($this->modelClass)::fromArray($row);
            }
            return $rows;

        } catch (\PDOException|\InvalidArgumentException $e) {
            print $e->getMessage() . "\n";
            return [];
        }
    }

    private function filtered(array $filters, array &$params, string &$sql): void
    {
        if (!empty($filters)) {
            $where = [];
            foreach ($filters as $column => $value) {
                if (!in_array($column, static::SELECT_COLUMNS, true)) {
                    throw new \InvalidArgumentException("Colonne non autorisée: $column");
                }
                $where[] = "$column = :$column";
                $params[":$column"] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }
    }

    /**
     * @return Model|null
     */
    protected function selectLast(): Model|null
    {
        $result = $this->select(limit: 1);
        return $result[0] ?? null;
    }

    /**
     * @param array $filters
     * @return Model|null
     */
    public function selectUnique(array $filters = []): Model|null
    {
        $result = $this::select($filters, limit: 1);
        return $result[0] ?? null;
    }

    /**
     * @param array $filters
     * @return int
     */
    public function count(array $filters = []): int
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->table}";
            $params = [];
            $this->filtered($filters, $params, $sql);
            $cursor = Database::getPdo()->prepare($sql);
            $cursor->execute($params);
            $row = $cursor->fetch(PDO::FETCH_ASSOC);
            return $row !== false ? (int)$row['total'] : 0;
        } catch (\PDOException $e) {
            print $e->getMessage() . "\n";
            return 0;
        }
    }

    /**
     * @param int   $id
     * @param array $columnsToUpdate
     * @return bool
     */
    public function update(int $id, array $columnsToUpdate): bool
    {
        try {
            if (empty($columnsToUpdate)) {
                throw new \InvalidArgumentException('Aucune colonne à mettre à jour');
            }

            foreach (array_keys($columnsToUpdate) as $column) {
                if (!in_array($column, static::SELECT_COLUMNS, true)) {
                    throw new \InvalidArgumentException("Colonne non autorisée: $column");
                }
            }

            $setClauses = [];
            $params = [];
            foreach ($columnsToUpdate as $column => $value) {
                $param = ':' . $column;
                $setClauses[] = "$column = $param";
                $params[$param] = $value;
            }

            $params[':id'] = $id;

            $sql = "UPDATE {$this->table} SET " . implode(', ', $setClauses) . " WHERE {$this->nameID} = :id";

            $stmt = Database::getPdo()->prepare($sql);
            return $stmt->execute($params);

        } catch (\PDOException|\InvalidArgumentException $e) {
            print $e->getMessage() . "\n";
            return false;
        }
    }


}
