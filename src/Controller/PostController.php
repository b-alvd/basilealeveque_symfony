<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/post/new', name: 'app_post')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Assigner l'ID de l'utilisateur connecté
            $post->setIdUserPost($this->security->getUser()->getId());
            $post->setCreatedAt(new \DateTimeImmutable());
            
            $em->persist($post);
            $em->flush();

            // Rediriger ou faire quelque chose après la sauvegarde
            return $this->redirectToRoute('app_index');
        }

        return $this->render('post/index.html.twig', [
            'postForm' => $form->createView()
        ]);
    }

    // #[Route('/post/{id}', name: 'app_post_show')]
    // public function show(): Response
    // {
    //     return $this->render('post/show.html.twig', [
    //         'controller_name' => 'PostController',
    //     ]);
    // }

    // #[Route('/post/all', name: 'app_post_all')]
    // public function all(): Response
    // {
    //     return $this->render('post/all.html.twig', [
    //         'controller_name' => 'PostController',
    //     ]);
    // }

    // #[Route('/post/{id}/edit', name: 'app_post_edit')]
    // public function edit(): Response
    // {
    //     return $this->render('post/edit.html.twig', [
    //         'controller_name' => 'PostController',
    //     ]);
    // }

    // #[Route('/post/{id}/delete', name: 'app_post_delete')]
    // public function delete(): Response
    // {
    //     return $this->render('post/delete.html.twig', [
    //         'controller_name' => 'PostController',
    //     ]);
    // }
}
