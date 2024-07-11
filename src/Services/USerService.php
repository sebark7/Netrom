<?php

namespace App\Services;

use App\Entity\Exercise;
use App\Entity\MuscleGroup;
use App\Entity\User;
use App\Repository\ExerciseRepository;
use App\Repository\MuscleGroupRepository;
use App\Repository\UserRepository;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class USerService
{
    private $exerciseRepository, $userRepository;

    /**
     */
    public function __construct(ExerciseRepository $exerciseRepository,
                                UserRepository $userRepository)
    {
        $this->exerciseRepository = $exerciseRepository;
        $this->userRepository = $userRepository;
    }

    public function findById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    public function verificationUniqueName(Exercise $exercise): array
    {
        //$exerciseNew = $this->exerciseRepository->findById($exercise->getId());

        $status = $this->saveExercise($exercise);

        if (key($status) == 'success') {
            $this->exerciseRepository->updateExercise($exercise);
            return $status;
        }

        return $status;

    }

    public function saveExercise(Exercise $exercise): array
    {

        try {

            $exerciseNew = $this->exerciseRepository->findType($exercise->getName(), $exercise->getId());

            if ($exerciseNew) {
                throw new \Exception("Exercise name already exist");
            }

        } catch (\Exception $exception) {
            return ['error' => true, 'messages' => $exception->getMessage()];
        }

        return ['success' => true];
    }


    public function verificationUser(User $user, int $idExercise)
    {
        $workouts = $user->getWorkouts();

        foreach ($workouts as $work)
        {
            $logs = $work->getExerciseLogs();
            foreach ($logs as $log)
            {
                if($log->getExercise()->getId() == $id)
                {
                    return false;
                }
            }
        }

        return true;

    }


}