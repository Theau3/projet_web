<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ObjectifsController extends AbstractController
{
    #[Route('/objectifs/{page}', name: 'objectifs')]
    public function objectifs(): Response
    {
        $url = $_SERVER['REQUEST_URI'];
        return $this->render('objectifs.html.twig',[
                                'url' => $url,
    ]);
    }
}