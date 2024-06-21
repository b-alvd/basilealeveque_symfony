<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post/new', name: 'app_post')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $post->setPostComment(0);
            $post->setPostLike(0);
            $post->setCreatedAt(new \DateTimeImmutable());

            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('app_index');
        }

        return $this->render('post/index.html.twig', [
            'postForm' => $form->createView()
        ]);
    }

    #[Route('/post/{id}', name: 'app_post_show')]
    public function show(Post $post, Request $request, PostRepository $postRepository, EntityManagerInterface $em, int $id): Response
    {
        $post = $postRepository->find($id);

        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setPost($post);
            $comment->setCreatedAt(new \DateTimeImmutable());

            $em->persist($comment);
            $em->flush();
        
            return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'commentForm' => $commentForm->createView()
        ]);
    }

    #[Route('/post/{id}/like', name: 'app_post_like', methods: ['POST'])]
    public function like(Post $post, PostRepository $postRepository, EntityManagerInterface $em, int $id): Response
    {
        $post = $postRepository->find($id);
        $user = $this->getUser();

        $like = $post->getLikes()->filter(fn($like) => $like->getUser() === $user)->first();

        if ($like) {
            $em->remove($like);
        } else {
            $like = new Like();
            $like->setUser($user);
            $like->setPost($post);
            $em->persist($like);
        }

        $em->flush();

        return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
    }

    #[Route('/post/{id}/edit', name: 'app_post_edit')]
    public function edit(Request $request, int $id, EntityManagerInterface $em): Response
    {
        $post = $em->getRepository(Post::class)->find($id);
        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_profile', ['username' => $post->getUser()->getUsername()]);
        }

        return $this->render('post/edit.html.twig', [
            'postForm' => $form->createView(),
            'post' => $post,
        ]);
    }

    #[Route('/post/{id}/delete', name: 'app_post_delete')]
    public function delete(Post $post, EntityManagerInterface $em): Response
    {
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('app_profile', ['username' => $post->getUser()->getUsername()]);
    }
}
