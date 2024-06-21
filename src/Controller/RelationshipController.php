<?php

namespace App\Controller;

use App\Entity\Relationship;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RelationshipController extends AbstractController
{
    #[Route('/relationship/send-request/{username}', name: 'send_friend_request')]
    public function sendFriendRequest(Request $request, string $username, EntityManagerInterface $em): Response
    {
        $user1 = $this->getUser();
        $user2 = $em->getRepository(User::class)->findOneBy(['username' => $username]);

        if (!$user2) {
            throw $this->createNotFoundException('User not found');
        }

        $relationship = new Relationship();
        $relationship->setUser1($user1);
        $relationship->setUser2($user2);
        $relationship->setStatus('pending');

        $em->persist($relationship);
        $em->flush();

        return $this->redirectToRoute('app_profile_all');
    }

    #[Route('/relationship/annul-request/{username}', name: 'annul_friend_request')]
    public function annulFriendRequest(string $username, EntityManagerInterface $em): Response
    {
        $user1 = $this->getUser();
        $user2 = $em->getRepository(User::class)->findOneBy(['username' => $username]);

        $relationship = $em->getRepository(Relationship::class)->findOneBy([
            'user1' => $user1,
            'user2' => $user2,
            'status' => 'pending'
        ]);

        if ($relationship) {
            $em->remove($relationship);
            $em->flush();
        }

        return $this->redirectToRoute('app_profile_all');
    }

    #[Route('/relationship/all-requests', name:'all_friend_requests')]
    public function allFriendRequests(Request $request, EntityManagerInterface $em): Response
    {
        $requestedRelations = $em->getRepository(Relationship::class)->findBy(['user2' => $this->getUser(), 'status' => 'pending']);
        
        return $this->render('profile/all_friend_requests.html.twig', [
            'requestedRelations' => $requestedRelations
        ]);
    }

    #[Route('/relationship/accept-request/{username}', name: 'accept_friend_request')]
    public function acceptFriendRequest(string $username, EntityManagerInterface $em): Response
    {
        $user1 = $this->getUser();
        $user2 = $em->getRepository(User::class)->findOneBy(['username' => $username]);

        $relationship = $em->getRepository(Relationship::class)->findOneBy([
            'user1' => $user2,
            'user2' => $user1,
            'status' => 'pending'
        ]);

        if ($relationship) {
            $relationship->setStatus('accepted');
            $em->flush();
        }

        return $this->redirectToRoute('all_friend_requests');
    }

    #[Route('/relationship/reject-request/{username}', name: 'reject_friend_request')]
    public function rejectFriendRequest(string $username, EntityManagerInterface $em): Response
    {
        $user1 = $this->getUser();
        $user2 = $em->getRepository(User::class)->findOneBy(['username' => $username]);

        $relationship = $em->getRepository(Relationship::class)->findOneBy([
            'user1' => $user2,
            'user2' => $user1,
            'status' => 'pending'
        ]);

        if ($relationship) {
            $em->remove($relationship);
            $em->flush();
        }

        return $this->redirectToRoute('all_friend_requests');
    }

    #[Route('/relationship/delete/{username}', name: 'delete_friend')]
    public function deleteFriend(string $username, EntityManagerInterface $em): Response
    {
        $relationship = $em->getRepository(Relationship::class)->findOneBy([
            'user1' => $this->getUser(),
            'user2' => $em->getRepository(User::class)->findOneBy(['username' => $username]),
            'status' => 'accepted'
        ]);

        if ($relationship) {
            $em->remove($relationship);
            $em->flush();
        }

        return $this->redirectToRoute('app_profile_all');
    }

    // #[Route('/relationship/block/{id}', name: 'block_user')]
    // public function blockUser(int $id, EntityManagerInterface $em): Response
    // {
    //     $relationship = $em->getRepository(Relationship::class)->find($id);

    //     if (!$relationship || $relationship->getUser1() !== $this->getUser()) {
    //         throw $this->createNotFoundException('Relationship not found or not authorized');
    //     }

    //     $relationship->setStatus('blocked');
    //     $em->flush();

    //     return $this->redirectToRoute('some_route');
    // }
}
