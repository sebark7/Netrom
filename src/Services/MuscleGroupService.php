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

    public function verificationUniqueValue(MuscleGroup $muscleGroup): array
    {
        $muscleGroupNew = $this->muscleGroupRepository->findType($muscleGroup->getTipul());

        if($muscleGroupNew == null)
        {
            //echo "DEBUG";
            //$this->muscleGroupRepository->saveMuscleGroup($muscleGroupNew);
            return $this->saveMuscleGroup($muscleGroup);
//            return true;
        }
        //echo "DEBUG2";
        return $this->saveMuscleGroup($muscleGroup);

    }

    public function saveMuscleGroup(MuscleGroup $muscleGroup) : array
    {

        try {
            $group = $this->muscleGroupRepository->findType($muscleGroup->getTipul());
            if($group)
            {
                throw new \Exception("Muscle Group already exist");
            }

        }catch(\Exception $exception)
        {
            return ['error' => true, 'messages' => $exception->getMessage()];

        }
        return ['success' => true];
    }

}