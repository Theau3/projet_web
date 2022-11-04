<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    public function home(): Response
    {
        $profile_picture = "pp1.png";
        $username = "testUsername";
        $title = "débutant";
        $level_percentage = 75;
        $level = 2;
        return $this->render('home.html.twig',[
            'profile_picture' => $profile_picture,
            'username' => $username,
            'title' => $title,
            'level_percentage' => $level_percentage,
            'level' => $level
        ]);
    }
}