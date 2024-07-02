<?php

namespace App\Services;

use App\Entity\MuscleGroup;
use App\Repository\MuscleGroupRepository;

class MuscleGroupService
{
    private $muscleGroupRepository;

    /**
     * @param $muscleGroupRepository
     */
    public function __construct(MuscleGroupRepository $muscleGroupRepository)
    {
        $this->muscleGroupRepository = $muscleGroupRepository;
    }

    public function verificationUniqueValue(MuscleGroup $muscleGroup): bool
    {
        $muscleGroupNew = $this->muscleGroupRepository->findType($muscleGroup->getTipul());

        if($muscleGroupNew == null)
        {
            echo "DEBUG";
            //$this->muscleGroupRepository->saveMuscleGroup($muscleGroupNew);
            return true;
        }
        echo "DEBUG2";
        return false;

    }

}