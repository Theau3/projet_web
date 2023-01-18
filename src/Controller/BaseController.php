<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController{
    #[Route('/', name: 'app_base')]
    public function index(){
        return $this->redirect('/home/objectifs');
    }
}