<?php

namespace App\Controller;

use App\Entity\Exercise;
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

class WorkoutController extends AbstractController
{
    #[Route('/workout', name: 'app_workout')]
    public function index(WorkoutRepository $repository): Response
    {
        $user = $this->getUser();
        $workouts = null;

        if(in_array('ROLE_TRAINER', $user->getRoles()))
        {
            $workouts = $repository->findAll();
        }
        else
        {
            $workouts = $user->getWorkouts();
        }

        return $this->render('workout/index.html.twig', [
            'controller_name' => 'WorkoutController',
            'workouts' => $workouts,
        ]);
    }

    #[Route('/workout/add', name: 'app_workout_add')]
    public function addWorkout(Request $request,
                                WorkoutRepository $repository): Response
    {

        $workout = new Workout();

        $form = $this->createForm(WorkoutType::class, $workout);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $workout = $form->getData();

            $user = $this->getUser();

            if($user)
            {
                $workout->setPerson($user);
            }

            $repository->saveWorkout($workout);

            return $this->redirectToRoute('app_workout');
        }

        return $this->render('workout/workout_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
