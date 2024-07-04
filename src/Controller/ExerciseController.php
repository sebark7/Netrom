<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Form\ExerciseType;
use App\Repository\ExerciseRepository;
use App\Repository\MuscleGroupRepository;
use App\Services\ExerciseService;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExerciseController extends AbstractController
{
    #[Route('/exercises', name: 'app_exercise')]
    public function index(ExerciseRepository $repository): Response
    {
        $exercise = $repository->findAll();

        return $this->render('exercise/exercises.html.twig', [
            'controller_name' => 'ExerciseController',
            'exercises' => $exercise
        ]);
    }

    #[Route('/exercise/add', name: 'app_exercise_add')]
    public function addExercise(Request               $request,
                                ExerciseRepository $repository,
                                MuscleGroupRepository $muscleGroupRepository): Response
    {

        $exercise = new Exercise();

        $form = $this->createForm(ExerciseType::class, $exercise);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $exercise = $form->getData();

            $muscleGroup = $muscleGroupRepository->findType($exercise->getMuscleGroup()->getTipul());

            if ($muscleGroup) {
                $exercise->setMuscleGroup($muscleGroup);
            }

            $repository->saveExercise($exercise);

            //die();
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_exercise');
        }

        return $this->render('exercise/exercise_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/exercise/{id}', name: 'app_exercise_id')]
    public function exerciseView(int $id, ExerciseRepository $repository): Response
    {
        $exercise = $repository->findById($id);

        return $this->render('exercise/exercises.html.twig', [
            'controller_name' => 'ExerciseController',
            'exercise' => $exercise
        ]);
    }

    #[Route('/exercise/update/{id}', name: 'app_exercise_update', methods: ['GET', 'PUT'])]
    public function exerciseUpdate(Request $request, int $id, ExerciseRepository $repository,
                                   ExerciseService $service): Response
    {

        $exercise = $service->findById($id);


        $form = $this->createForm(ExerciseType::class, $exercise, [
            'method' => 'PUT',
        ]);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $exercise = $form->getData();

            $message = $service->verificationUniqueName($exercise);

            if(key($message) == "error")
            {
                $error = $message['messages'];

                $this->addFlash('error', $error);
                $this->addFlash('info', 'CEVA');
                return $this->redirectToRoute('app_exercise_update', [
                    'id' => $id,
                ]);
            }
            return $this->redirectToRoute('app_exercise');
        }
        
        return $this->render('exercise/exercise_update.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/exercise/delete/{id}', name: 'app_exercise_delete', methods: ['DELETE'])]
    public function exerciseDelete(Request $request, int $id, ExerciseRepository $repository): Response
    {


        $exercise = $repository->findById($id);
        dump($exercise);

        $repository->deleteById($exercise->getId());

        return $this->redirectToRoute('app_exercise');

    }


}
