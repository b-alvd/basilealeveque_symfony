<?php

namespace App\Controller;

use App\Entity\Relationship;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route(path:"/profile/all", name:"app_profile_all")]
    public function allProfiles(EntityManagerInterface $em, UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('profile/all_profiles.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/profile/{username}', name: 'app_profile')]
    public function index(PostRepository $postRepository, string $username, EntityManagerInterface $em): Response
    {
        $currentUser = $this->getUser();
        $user = $em->getRepository(User::class)->findOneBy(['username' => $username]);

        if (!$currentUser || $currentUser->getUsername() !== $username) {
            $user = $em->getRepository(User::class)->findOneBy(['username' => $username]);
            if (!$user) {
                throw $this->createNotFoundException('User not found');
            }
            $posts = $postRepository->findBy(['user' => $user], ['createdAt' => 'DESC']);
        } else {
            $posts = $postRepository->findBy(['user' => $currentUser], ['createdAt' => 'DESC']);
        }

        $isPending = $em->getRepository(Relationship::class)->findOneBy([
            'user1' => $currentUser,
            'user2' => $user,
            'status' => 'pending'
        ]);

        $isFriend = $em->getRepository(Relationship::class)->findOneBy([
            'user1' => $currentUser,
            'user2' => $user,
            'status' => 'accepted'
        ]) || $em->getRepository(Relationship::class)->findOneBy([
            'user1' => $user,
            'user2' => $currentUser,
            'status' => 'accepted'
        ]);

        if ($currentUser === $user) {
            $friendshipCount = $em->getRepository(Relationship::class)->count([
                'user1' => $currentUser,
                'status' => 'accepted'
            ]);
        } else {
            $friendshipCount = $em->getRepository(Relationship::class)->count([
                'user1' => $currentUser,
                'user2' => $user,
                'status' => 'accepted'
            ]) + $em->getRepository(Relationship::class)->count([
                'user1' => $user,
                'user2' => $currentUser,
                'status' => 'accepted'
            ]);
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user ?? $currentUser,
            'posts' => $posts,
            'isPending' => $isPending,
            'isFriend' => $isFriend,
            'friendshipCount' => $friendshipCount,
        ]);
    }
}
