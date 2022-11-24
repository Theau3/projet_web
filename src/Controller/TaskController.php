<?php

// src/Controller/TaskController.php
namespace App\Controller;

use App\Form\Type\TaskType;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends AbstractController
{
    public function new(Request $request): Response
    {
        // just set up a fresh $task object (remove the example data)
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            // ... perform some action, such as saving the task to the database

            //return $this->redirectToRoute('task_success');
        }

        return $this->renderForm('task/new.html.twig', [
            'form' => $form,
        ]);
    }
}