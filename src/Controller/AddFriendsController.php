<?php

namespace App\Controller;

use App\Entity\Friends;
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


class AddFriendsController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }


    #[Route('/addfriends', name: 'Add_Friends')]
    public function addfriends(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user1 = $this->security->getUser();
        $user2 = new User();
        $amitie = new Friends();
        $form = $this->createForm(SearchFormType::class, $amitie, [
            'action' => $this->generateUrl('Add_Friends'),
            'method' => 'GET',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $nom = $form->get('name')->getData();
                $amis = $userRepository->findOneBy(['username' => $nom]);
                if ($amis) {
                    $user2 = $amis;
                    $amitie->setUser1($user1);
                    $amitie->setUser2($user2);
                    $entityManager->persist($amitie);
                    $entityManager->flush();
                    return $this->redirect('/home/addfriends?tab=nom?form=ok');
                }
            }
            return $this->redirect('/home/addfriends?tab=nom?form=error');
        }
        $url = $_SERVER['REQUEST_URI'];

        return $this->renderForm('Amis/Addfriends.html.twig', [
            'form' => $form,
            'url' => $url,
        ]);
    }
}
