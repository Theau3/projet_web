<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class FriendsController extends AbstractController
{
    public function friends(): Response
    {
        return $this->render('friends.html.twig');
    }
}
