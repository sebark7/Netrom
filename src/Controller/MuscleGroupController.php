<?php

namespace App\Controller;

use App\Entity\MuscleGroup;
use App\Form\MuscleGroupType;
use App\Repository\ExerciseRepository;
use App\Repository\MuscleGroupRepository;
use App\Services\MuscleGroupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MuscleGroupController extends AbstractController
{

    #[Route('/muscle_groups', name: 'app_muscle_group')]
    public function index(MuscleGroupRepository $repository): Response
    {
        $muscleGroups = $repository->findAll();

        return $this->render('muscle_group/muscle_groups.html.twig', [
            'controller_name' => 'MuscleGroupController',
            'muscle_groups' => $muscleGroups
        ]);
    }


    #[Route('/muscle_group', name: 'app_muscle_group_name')]
    public function muscleGroupView(MuscleGroupRepository $repository,
                                    Request $request): Response
    {

        $name = $request->query->get('name', '');

        $muscleGroup = $repository->findType($name);

        return $this->render('muscle_group/index.html.twig', [
            'controller_name' => 'MuscleGroupController',
            'muscle_group' => $muscleGroup
        ]);
    }

    #[Route('/muscle_group/add', name: 'add_muscle_group')]
    public function store(Request $request, MuscleGroupRepository $repository,
                          MuscleGroupService $muscleGroupService): Response
    {


        $muscleGroup = new MuscleGroup();

        $form = $this->createForm(MuscleGroupType::class, $muscleGroup);

        $form->handleRequest($request);

        $error = null;

        if ($form->isSubmitted() && $form->isValid())
        {

            $muscleGroup = $form->getData();

            $message = $muscleGroupService->verificationUniqueValue($muscleGroup);

            if(key($message) == "error")
            {
                $error = $message['messages'];
                return $this->render('muscle_group/addMuscleGroup.html.twig', [
                    'form' => $form,
                    'error' => $error
                ]);
            }

            $repository->saveMuscleGroup($muscleGroup);

            return $this->redirectToRoute('app_muscle_group');
        }

        return $this->render('muscle_group/addMuscleGroup.html.twig', [
            'form' => $form,
            'error' => $error
        ]);

    }


//
//    #[Route('/muscle_group/{name}', name: 'app_muscle_group_id')]
//    public function muscleGroupView(string $tipul, ExerciseRepository $repository): Response
//    {
//        $muscle_group = $repository->findType($tipul);
//
//        return $this->render('exercise/exercises.html.twig', [
//            'controller_name' => 'MuscleGroupController',
//            'muscle_group' => $muscle_group
//        ]);
//    }


}
