<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class FriendsController extends AbstractController
{
    public function Addfriends(): Response
    {
        return $this->render('Amis/Addfriends.html.twig');
    }
}
