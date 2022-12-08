<?php

// src/Controller/TaskController.php
namespace App\Controller;

use App\Entity\Task;
use App\Form\Type\TaskPersoType;
use App\Entity\TaskPerso;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class TaskController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }


    #[Route('/TaskPerso', name: 'task_perso')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $taskPerso = new TaskPerso();
        $task = new Task();
        $user = $this->security->getUser();
        $form = $this->createForm(TaskPersoType::class, $taskPerso);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $taskPerso = $form->getData();
            $taskPerso->setRepetition(0);
            $taskPerso->setUser($user);

            $dateDebut = $taskPerso->getDateDebut();
            $dateFin = $taskPerso->getDueDate();
            $dateIntervalle = $dateDebut->diff($dateFin);
            $nombreJours = $dateIntervalle->days;

            $task = $taskPerso -> getTask();
            $nombreFois = $task->getNombreFois();
            $choixTemps = $task->getChoixTemps();
            if($nombreFois == null){//on ne souhaite pas utiliser l'avancement par période de temps
                $avancement = 1;//Dans ce cas on ne valide l'objectif qu'une fois : lorsqu'il est réalisé
            }
            else{
                $nombreFrequence = $nombreJours % $choixTemps;
                $avancement = $nombreFois * $nombreFrequence;
            }

            $task->setAvancement($avancement);
            $task -> setPerso(True);
            $category = $task -> getCategory();


            $entityManager->persist($taskPerso);
            $entityManager->persist($task);
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('HomeController');
        }

        return $this->render('task/new.html.twig', [
            'task_perso' => $form->createView(),
        ]);
    }
}