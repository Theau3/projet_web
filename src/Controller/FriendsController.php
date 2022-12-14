<?php

namespace App\Controller;

use App\Repository\FriendsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;


class FriendsController extends AbstractController
{


    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }


    #[Route('/', name: 'liste_amis', methods: ['GET'])]
    public function friends(FriendsRepository $friendsRepository): Response
    { {
            $user = $this->security->getUser();
            $amis = $friendsRepository->findBy(['user1' => $user]);

            return $this->render('Amis/friends.html.twig', [
                'amis' => $amis
            ]);
        }
    }
}
