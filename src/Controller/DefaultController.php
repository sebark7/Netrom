<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $user = $this->getUser();
        $mimeType = null;
        $imageData= null;
        $stringData = null;

        if($user!=null)
        {
            $imageData = $user->getImageData();
            $mimeType = $user->getImageMimeType();
            if($imageData!=null)
            {
                $stringData = stream_get_contents($imageData);
                $stringData = base64_encode($stringData);
            }

        }

        return $this->render('home/home.html.twig', [
            'controller_name' => 'DefaultController',
            'user' => $user,
            'mimeType' => $mimeType,
            'imageData' => $stringData,
        ]);
    }
}