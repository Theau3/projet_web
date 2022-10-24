<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TestController extends AbstractController
{
    public function test(): Response
    {
        $string = "test1234";
        return $this->render('test.html.twig',[
            'test' => $string
        ]);
    }
}