<?php

namespace App\Controller;

use App\Repository\FriendsRepository;
use App\Entity\Friends;
use App\Entity\Notification;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

use App\Form\Type\FriendsType;
use App\Form\Type\SearchFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;


class NotificationController extends AbstractController
{

    #[Route("/friends2", name: "friends_index")]
    public function index(FriendsRepository $friendshipRepository)
    {
        $user = $this->getUser();

        $acceptedFriendships = $friendshipRepository->findAcceptedFriendshipsForUser($user);
        $url = $_SERVER['REQUEST_URI'];
        return $this->render('Amis/index.html.twig', [
            'friends' => $acceptedFriendships,
            'url' => $url
        ]);
    }

    #[Route("/demande", name: "friends_demande")]
    public function demande(FriendsRepository $friendshipRepository)
    {
        $user = $this->getUser();

        $pendingFriendshipRequests = $friendshipRepository->findPendingFriendshipRequestsForUser($user);

        return $this->render('Amis/attente.html.twig', [
            'friendss' => $pendingFriendshipRequests
        ]);
    }


    /**
     * @Route("/friends/accept-request/{id}", name="friends_accept_request")
     */
    //#[Route("/friends2", name: "friends_accept_request")]
    public function acceptFriendshipRequest(int $id, FriendsRepository $friendshipRepository)
    {
        $user = $this->getUser();
        $friendship = $friendshipRepository->find($id);
        if (!$friendship || $friendship->getUser2() !== $user || $friendship->getStatus() !== Friends::STATUS_PENDING) {
            throw $this->createNotFoundException('Demande d\'amitié introuvable');
        }

        $friendship->setStatus(Friends::STATUS_ACCEPTED);
        $friendshipRepository->save($friendship);

        $this->addFlash('success', 'Demande d\'amitié acceptée');

        return $this->redirectToRoute('friends_demande');
    }

    #[Route("/friends/decline-request/{id}", name: "friends_decline_request")]
    public function declineFriendshipRequest(int $id, FriendsRepository $friendshipRepository)
    {
        $user = $this->getUser();
        $friendship = $friendshipRepository->find($id);
        if (!$friendship || $friendship->getUser2() !== $user || $friendship->getStatus() !== Friends::STATUS_PENDING) {
            throw $this->createNotFoundException('Demande d\'amitié introuvable');
        }
        $friendship->setStatus(Friends::STATUS_DECLINED);
        $friendshipRepository->save($friendship);

        $this->addFlash('success', 'Demande d\'amitié refusée');

        return $this->redirectToRoute('friends_demande');
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
