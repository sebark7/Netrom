<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\USerType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class UserController extends AbstractController
{


    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        $user = $this->getUser();

        $imageData = null;
        $mimeType = null;
        $stringData = null;

        if($user != null)
        {
            $imageData = $user->getImageData();
            $mimeType = $user->getImageMimeType();
            if($imageData !=null)
            {
                $stringData = stream_get_contents($imageData);
                $stringData = base64_encode($stringData);
            }
        }

        return $this->render('user/user.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user,
            'mimeType' => $mimeType,
            'imageData' => $stringData,
        ]);
    }



    #[Route('/user/{id}', name: 'app_user_id_retrieving')]
    public function indexId(int $id, UserRepository $repository): Response
    {
        $user = $repository->findId($id);

        $imageData = $user->getImageData();
        $mimeType = $user->getImageMimeType();

        $stringData = null;

        if($imageData != null)
        {
            $stringData = stream_get_contents($imageData);
            $stringData = base64_encode($stringData);
        }

        return $this->render('user/user.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user,
            'mimeType' => $mimeType,
            'imageData' => $stringData,
        ]);
    }


    #[Route('/users', name: 'app_users_id', methods: ['GET'])]
    public function userList(UserRepository $repository): Response
    {
        $userList = $repository->findAll();

        return $this->render('user/users.html.twig', [
            'userList' => $userList,
        ]);
    }


    #[Route('/user/register', name: 'register_user')]
    public function store(Request $request, UserRepository $repository,
    UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = new User();

        $form = $this->createForm(USerType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $form->getData();

            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $imageData = file_get_contents($imageFile->getPathname());
                $user->setImageData($imageData);
                $user->setImageMimeType($imageFile->getMimeType());
            }

            $password = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

//            dd($form->getData());
            $repository->saveUser($user);
            //die();
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_users');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form
        ]);
    }


    #[Route('/user/image/{id}', name: 'user_image')]
    public function showImage(int $id, UserRepository $repository): Response
    {
        $user = $repository->findBy(['id' => $id]);
        $imageData = $user->getImageData();
        $mimeType = $user->getImageMimeType();

        if (!$imageData) {
            throw $this->createNotFoundException('No image found');
        }

        $response = new Response($imageData);
        $response->headers->set('Content-Type', $mimeType);

        return $response;
    }

}