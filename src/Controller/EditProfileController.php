<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditProfileController extends AbstractController
{
    #[Route('/profile/{username}/edit', name: 'app_profile_edit')]
    public function index(): Response
    {
        

        return $this->render('edit_profile_controler/index.html.twig', [
            'controller_name' => 'EditProfileController',
        ]);
    }
}
