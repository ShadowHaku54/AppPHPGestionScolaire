<?php
declare(strict_types=1);

namespace App\Service;

use App\Config\Validator;
use App\EnumForm\InputMessage;
use App\Model\Module;
use App\Repository\ModuleRepository;

class ModuleService
{
    private ModuleRepository $repo;

    public function __construct()
    {
        $this->repo = new ModuleRepository();
    }

    public function existsByNom(string $nom): bool
    {
        return $this->getModuleByNom($nom) !== null;
    }

    public function existsById(int $id): bool
    {
        return $this->getModuleById($id) !== null;
    }

    public function ajouterModule(Module $module, Validator $validator): bool
    {
        if ($this->existsByNom($module->getNom())) {
            $validator->addError("nomModuleExiste", "Cet module existe déjà");
            return false;
        }

        return $this->repo->insert($module);
    }

    /**
     * @param int $offset
     * @param int|null $limit
     * @return Module[]
     */
    public function getModules(
        int|null $id = null,
        string|null $nom = null,
        int $offset = 0,
        int|null $limit = null
    ): array
    {
        $filters = $this->makeFilters($id, $nom);
        return $this->repo->select($filters, $offset, $limit);
    }

    private function makeFilters(int|null $id = null, string|null $nom = null): array
    {
        $filters = [];
        if ($id !== null) {
            $filters['id_module'] = $id;
        }
        if ($nom !== null) {
            $filters['nom'] = $nom;
        }
        return $filters;
    }

    public function getNbModules(int|null $id = null, string|null $nom = null): int
    {
        $filters = $this->makeFilters($id, $nom);
        return $this->repo->count($filters);
    }


    public function getModuleById(int $id): Module|null
    {
        return $this->repo->selectUnique(['id_module' => $id]);
    }

    public function getModuleByNom(string $nom): Module|null
    {
        return $this->repo->selectUnique(['nom' => $nom]);
    }
}
