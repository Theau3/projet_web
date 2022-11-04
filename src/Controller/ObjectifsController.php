<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ObjectifsController extends AbstractController
{
    public function objectifs(): Response
    {
        return $this->render('objectifs.html.twig');
    }
}