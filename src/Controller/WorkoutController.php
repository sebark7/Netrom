<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Entity\ExerciseLog;
use App\Entity\Workout;
use App\Form\ExerciseType;
use App\Form\WorkoutType;
use App\Repository\ExerciseRepository;
use App\Repository\MuscleGroupRepository;
use App\Repository\UserRepository;
use App\Repository\WorkoutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function Webmozart\Assert\Tests\StaticAnalysis\length;

class WorkoutController extends AbstractController
{
    #[Route('/workout', name: 'app_workouts')]
    public function index(WorkoutRepository $repository): Response
    {
        $user = $this->getUser();
        $workouts = null;

        if (in_array('ROLE_TRAINER', $user->getRoles())) {
            $workouts = $repository->findAll();
        } else {
            $workouts = $user->getWorkouts();
        }

        return $this->render('workout/index.html.twig', [
            'controller_name' => 'WorkoutController',
            'workouts' => $workouts,
        ]);
    }

    #[Route('/workout/add', name: 'app_workout_add')]
    public function addWorkout(Request           $request,
                               WorkoutRepository $repository): Response
    {

        $workout = new Workout();

        $form = $this->createForm(WorkoutType::class, $workout);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $workout = $form->getData();

            $user = $this->getUser();

            if ($user) {
                $workout->setPerson($user);
            }

            $repository->saveWorkout($workout);

            return $this->redirectToRoute('app_workouts');
        }

        return $this->render('workout/workout_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/workout/{id}', name: 'app_workout_id', requirements: ['id' => '\d+'])]
    public function retrieveWorkoutUser(int $id, WorkoutRepository $repository): Response
    {

        /** @var Workout $workout */
        $workout = $repository->findOneBy(['id' => $id]);

        if (!$workout) {
            throw $this->createNotFoundException('Workout not found');
        }

        $exerciseLogs = $workout->getExerciseLogs();
        $exercises = [];
        $calories = [];

        foreach ($exerciseLogs as $log) {
            $exercises[] = $log->getExercise();
            $calories[] = $log->getDuration() * ($log->getReps() * $log->getSets());
        }

        return $this->render('workout/workout_specific.html.twig', [
            'controller_name' => 'WorkoutController',
            'workout' => $workout,
            'exercises' => $exercises,
            'calories' => $calories,
        ]);
    }
}
