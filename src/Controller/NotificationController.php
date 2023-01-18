<?php

namespace App\Controller;

use App\Repository\FriendsRepository;
use App\Entity\Friends;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;



class NotificationController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }

    #[Route("/friends2", name: "friends_index")]
    public function index(FriendsRepository $friendshipRepository)
    {
        $user = $this->security->getUser();

        $acceptedFriendships = $friendshipRepository->findAcceptedFriendshipsForUser($user);
        $url = $_SERVER['REQUEST_URI'];
        return $this->render('Amis/index.html.twig', [
            'user' => $user,
            'friends' => $acceptedFriendships,
            'url' => $url
        ]);
    }

    #[Route("/demande", name: "friends_demande")]
    public function demande(FriendsRepository $friendshipRepository)
    {
        $user = $this->security->getUser();

        $pendingFriendshipRequests = $friendshipRepository->findPendingFriendshipRequestsForUser($user);

        return $this->render('Amis/attente.html.twig', [
            'friendss' => $pendingFriendshipRequests
        ]);
    }



     #[Route("/friends/accept-request/{id}", name:"friends_accept_request")]
    public function acceptFriendshipRequest(int $id, FriendsRepository $friendshipRepository)
    {
        $user = $this->security->getUser();
        $friendship = $friendshipRepository->findOneBy(['id_amis' => $id]);
        if (!$friendship  || $friendship->getUser2() !== $user || $friendship->getStatus() !== Friends::STATUS_PENDING) {
            throw $this->createNotFoundException('Demande d\'amitié introuvable : ');
        }

        $friendship->setStatus(Friends::STATUS_ACCEPTED);
        $friendshipRepository->save($friendship, true);

        $this->addFlash('success', 'Demande d\'amitié acceptée');

        return $this->redirect('/home/friends?voir=tous?accept=ok');
    }

    #[Route("/friends/decline-request/{id}", name: "friends_decline_request")]
    public function declineFriendshipRequest(int $id, FriendsRepository $friendshipRepository)
    {
        $user = $this->security->getUser();
        $friendship = $friendshipRepository->findOneBy(['id_amis' => $id]);
        if (!$friendship || $friendship->getUser2() !== $user || $friendship->getStatus() !== Friends::STATUS_PENDING) {
            throw $this->createNotFoundException('Demande d\'amitié introuvable');
        }
        $friendship->setStatus(Friends::STATUS_DECLINED);
        $friendshipRepository->save($friendship, true);

        $this->addFlash('success', 'Demande d\'amitié refusée');

        return $this->redirect('/home/friends?voir=tous?accept=ko');
    }


    //SUPPRESSION OBJECTIFS
    #[Route('/friends/{id}/delete', name: 'friends_delete')]
    public function delete(string $id, EntityManagerInterface $entityManager, FriendsRepository $friendshipRepository): Response
    {
        $friends = $friendshipRepository->findOneBy(['id_amis' => $id]);
        $entityManager->remove($friends);
        $entityManager->flush();
        return $this->redirect('/home/friends?voir=tous?delete=ok');
    }
}
