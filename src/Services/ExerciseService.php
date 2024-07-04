<?php

namespace App\Services;

use App\Entity\Exercise;
use App\Entity\MuscleGroup;
use App\Repository\ExerciseRepository;
use App\Repository\MuscleGroupRepository;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class ExerciseService
{
    private $exerciseRepository;

    /**
     */
    public function __construct(ExerciseRepository $exerciseRepository)
    {
        $this->exerciseRepository = $exerciseRepository;
    }

    public function findById(int $id): ?Exercise
    {
        return $this->exerciseRepository->findById($id);
    }

    public function verificationUniqueName(Exercise $exercise): array
    {
        $exerciseNew = $this->exerciseRepository->findById($exercise->getId());

        $status = $this->saveExercise($exerciseNew);

        if(key($status) == 'success')
        {
            $this->exerciseRepository->updateExercise($exercise);
            return $status;
        }

        return $status;

    }

    public function saveExercise(Exercise $exercise) : array
    {

        try {

            $exerciseNew = $this->exerciseRepository->findType($exercise->getName());

            if($exerciseNew)
            {
                throw new \Exception("Exercise name already exist");
            }

        }catch(\Exception $exception)
        {
            return ['error' => true, 'messages' => $exception->getMessage()];
        }

        return ['success' => true];
    }


}