<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;


class HomeController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }
    #[Route('/home/{page}', name: 'app_home')]
    public function home(string $page, ManagerRegistry $doctrine, SessionInterface $session, Request $request): Response
    {
        //$repository = $doctrine->getRepository(User::class);
        //$username = $this->getUser()->getUserIdentifier();
        //$user = $repository->findOneBy(['username' => $username]);
        $user = $this->security->getUser();
        $username = $user->getUsername();
        $roles = $user->getRoles();
        $progression = $user->getProgression();
        $session->set('show_done', $request->request->get('show_done'));

        $profile_picture = $user->getPp();
        $level = $user->getLevel($progression);
        $title = $user->getTitre($level);
        $level_percentage = $user->getLevelPercentage($level, $progression);

        $url = $_SERVER['REQUEST_URI'];
        return $this->render('home.html.twig', [
            'profile_picture' => $profile_picture,
            'username' => $username,
            'title' => $title,
            'level_percentage' => $level_percentage,
            'level' => $level,
            'page' => $page,
            'roles' => $roles,
            'url' => $url,
        ]);
    }
}
