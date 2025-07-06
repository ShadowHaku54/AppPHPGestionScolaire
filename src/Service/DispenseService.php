<?php
declare(strict_types=1);

namespace App\Service;

use App\EnumForm\InputMessage;
use App\Model\Dispense;
use App\Model\Module;
use App\Model\Professeur;
use App\Repository\DispenseRepository;

class DispenseService
{
    private DispenseRepository $dispenseRepo;
    private ProfesseurService $profService;
    private ModuleService $moduleService;

    public function __construct()
    {
        $this->dispenseRepo = new DispenseRepository();
        $this->profService = new ProfesseurService();
        $this->moduleService = new ModuleService();
    }

    public function associerProfModule(Dispense $dispense): InputMessage
    {

        if (!$this->profService->existsById($dispense->getIdProfesseur())) {
            return InputMessage::ERROR_METIER_PROF_NOT_EXIST;
        }

        if (!$this->moduleService->existsById($dispense->getIdModule())) {
            return InputMessage::ERROR_METIER_MODULE_NOT_EXIST;
        }

        if ($this->existsByIdProfAndIdModule($dispense->getIdProfesseur(), $dispense->getIdModule())) {
            return InputMessage::ERROR_METIER_DISPENSE_INVALIDE;
        }

        return $this->dispenseRepo->insert($dispense)
            ? InputMessage::SUCCESS_FINALE_DISPENSE_CREATED
            : InputMessage::ERROR_FINALE_DISPENSE_NOT_CREATED;
    }

    public function existsByIdProfAndIdModule(int $idProfesseur, int $idModule): bool
    {
        return $this->getDispenseByIdProfAndIdModule($idProfesseur, $idModule) !== null;
    }

    public function existsById($idDispense): bool
    {
        return $this->getDispenseById($idDispense) !== null;
    }

    public function getDispenseByIdProfAndIdModule(int $idProfesseur, int $idModule): Dispense|null
    {
        return $this->dispenseRepo->selectUnique(['id_professeur' => $idProfesseur, 'id_module' => $idModule]);
    }

    public function getDispenseById(int $idDispense): Dispense|null
    {
        return $this->dispenseRepo->selectUnique(["id_dispense" => $idDispense]);
    }

    /**
     * @param int $idProfesseur
     * @param int $offset
     * @param int|null $limit
     * @return Dispense[]
     */
    public function getDispensesByProfesseur(int $idProfesseur, int $offset = 0, ?int $limit = null): array
    {
        return $this->dispenseRepo->select(['id_professeur' => $idProfesseur], $offset, $limit);
    }


    /**
     * @param int $idModule
     * @param int $offset
     * @param int|null $limit
     * @return Dispense[]
     */
    public function getDispensesByModule(int $idModule, int $offset = 0, ?int $limit = null): array
    {
        return $this->dispenseRepo->select(['id_module' => $idModule], $offset, $limit);
    }


    /**
     * @param int $idProfesseur
     * @param int $offset
     * @param int|null $limit
     * @return Module[]
     */
    public function getModulesByProfesseur(int $idProfesseur, int $offset = 0, ?int $limit = null): array
    {
        $dispenses = $this->getDispensesByProfesseur($idProfesseur, $offset, $limit);
        $modules = [];
        foreach ($dispenses as $dispense) {
            $module = $this->moduleService->getModuleById($dispense->getIdModule());
            if ($module !== null) {
                $modules[] = $module;
            }
        }
        return $modules;
    }


    /**
     * @param int $idModule
     * @param int $offset
     * @param int|null $limit
     * @return Professeur[]
     */
    public function getProfesseursByModule(int $idModule, int $offset = 0, ?int $limit = null): array
    {
        $dispenses = $this->getDispensesByModule($idModule, $offset, $limit);
        $professeurs = [];
        foreach ($dispenses as $dispense) {
            $professeur = $this->profService->getProfById($dispense->getIdProfesseur());
            if ($professeur !== null) {
                $professeurs[] = $professeur;
            }
        }
        return $professeurs;
    }
}
