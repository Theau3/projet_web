<?php

// src/Controller/TaskController.php
namespace App\Controller;

use App\Entity\Task;
use App\Form\Type\TaskPersoType;
use App\Entity\TaskPerso;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
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
        $category = new Category();
        $user = $this->security->getUser();
        $form = $this->createForm(TaskPersoType::class, $taskPerso);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $taskPerso = $form->getData();
            $taskPerso->setAvancement(0);
            $taskPerso->setRepetition(100);
            $taskPerso->setUser($user);
            $task = $taskPerso -> getTask();
            $task -> setPerso(True);
            $category = $task -> getCategory();
            $entityManager->persist($taskPerso);
            $entityManager->persist($task);
            $entityManager->persist($category);
            $entityManager->flush();
            //return $this->redirectToRoute('task_success');
        }

        return $this->renderForm('task/new.html.twig', [
            'form' => $form,
        ]);
    }
}