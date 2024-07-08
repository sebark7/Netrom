<?php

namespace App\Services;

use App\Entity\Exercise;
use App\Entity\MuscleGroup;
use App\Entity\User;
use App\Entity\Workout;
use App\Repository\ExerciseLogRepository;
use App\Repository\ExerciseRepository;
use App\Repository\MuscleGroupRepository;
use App\Repository\WorkoutRepository;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class WorkoutService
{
    private $workoutRepository, $exerciseLogRepository;

    /**
     */
    public function __construct(WorkoutRepository $workoutRepository, ExerciseLogRepository $exerciseLogRepository)
    {
        $this->workoutRepository = $workoutRepository;
        $this->exerciseLogRepository = $exerciseLogRepository;
    }

    public function saveWorkout(Workout $workout) : void
    {
        $exerciseLogRepositoryArray = $workout->getExerciseLogs();

        foreach ($exerciseLogRepositoryArray as $log)
        {
            $workout->addExerciseLog($log);
        }

        $this->workoutRepository->saveWorkout($workout);
    }


}