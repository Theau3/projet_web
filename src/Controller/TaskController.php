<?php

// src/Controller/TaskController.php
namespace App\Controller;

use App\Entity\Task;
use App\Form\Type\TaskPersoType;
use App\Entity\TaskPerso;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\TaskPersoRepository;
use Symfony\Component\Validator\Constraints\Length;

class TaskController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }

    //PARTIE AFFICHAGE
    #[Route('/objectifs/{page}', name: 'objectifs')]
    public function objectifs(): Response
    {
        $url = $_SERVER['REQUEST_URI'];
        return $this->render('objectifs.html.twig',[
                                'url' => $url,
    ]);
    }

    #[Route('/tous', name: 'liste_show')]
    public function showListe(TaskPersoRepository $taskPersoRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();
        $objectifs = $taskPersoRepository->findBy(['user' => $user]);

        if (!$objectifs) {
            $objectifs = [];
        }

        return $this->render('task/liste.html.twig', ['objectifs' => $objectifs, 'entityManager' => $entityManager]);
    }

    public function showListeCategories(TaskPersoRepository $taskPersoRepository, EntityManagerInterface $entityManager, string $idCat): Response
    {
        $user = $this->security->getUser();
        $objectifs = $taskPersoRepository->findBy(['user' => $user, 'category' => $idCat]);

        if (!$objectifs) {
            $objectifs = [];
        }

        return $this->render('task/liste.html.twig', ['objectifs' => $objectifs, 'entityManager' => $entityManager]);
    }


    #[Route('/objectif/{id}', name: 'objectif_show')]
    public function show(string $id, TaskPersoRepository $taskPersoRepository): Response
    {
        $user = $this->security->getUser();
        $objectifs = $taskPersoRepository->findBy(['user' => $user]);
        $objectif = $taskPersoRepository->findOneBy(['user' => $user, 'Task' => $id]);
        $index = array_search($objectif, $objectifs);//mettre l'index

        if (!$objectif) {
            throw $this->createNotFoundException(
                'Aucun objectif existe avec cet id : '.$id
            );
        }

            return $this->render('task/objectif.html.twig', ['objectif' => $objectif, 'objectifs' => $objectifs, 'index' => $index]);

    }

    #[Route('/objectif/{id}/valider', name: 'valider_objectif')]
    public function valider($id, EntityManagerInterface $entityManager, TaskPersoRepository $taskPersoRepository): Response
    {
        $user = $this->security->getUser();
        $objectif = $taskPersoRepository->findOneBy(['user' => $user, 'Task' => $id]);

        if($objectif->getRepetition()+1 == $objectif->getTask()->getAvancement()){
            $objectif->setIsDone(true);
            $user->setProgression($user->getProgression()+1);
        }

        $objectif->setRepetition($objectif->getRepetition()+1);
        $entityManager->flush();

        return $this->redirect('/home/objectifs?valider=ok');//rediriger vers la dernière page ?
    }

    //CREATION OBJECTIFS
    #[Route('/task/new', name: 'task_perso')]
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
            $taskPerso->setIsDone(false);
            $taskPerso->setIsFavoris(false);

            $dateDebut = $taskPerso->getDateDebut();
            $dateFin = $taskPerso->getDueDate();
            $dateIntervalle = $dateDebut->diff($dateFin);
            $nombreJours = $dateIntervalle->days;

            $task = $taskPerso->getTask();
            $nombreFois = $task->getNombreFois();
            $choixTemps = $task->getChoixTemps();
            if($nombreFois == null){//on ne souhaite pas utiliser l'avancement par période de temps
                $avancement = 1;//Dans ce cas on ne valide l'objectif qu'une fois : lorsqu'il est réalisé
            }
            else{
                $nombreFrequence = intdiv($nombreJours, $choixTemps);
                $avancement = $nombreFois * $nombreFrequence;
            }
            $task->setAvancement($avancement);
            $task->setPerso(True);
            $taskPerso -> setTask($task);
            $entityManager->persist($task);
            $entityManager->persist($taskPerso);
            $entityManager->flush();
            return $this->redirect('/home/objectifs?form=ok');;
        }

        return $this->render('task/new.html.twig', [
            'task_perso' => $form->createView(),
        ]);
    }

    //MODIFICATION OBJECTIFS
    #[Route('/objectif/{id}/edit', name: 'objectif_edit')]
    public function edit(Request $request,string $id, TaskPersoRepository $taskPersoRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();
        $taskPerso = $taskPersoRepository->findOneBy(['user' => $user, 'Task' => $id]);
        $form = $this->createForm(TaskPersoType::class, $taskPerso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskPersoRepository->save($taskPerso, true);
            return $this->redirect('/home/objectifs?edit=ok');
        }

        return $this->render('task/edit.html.twig', [
            'task_perso' => $form->createView(),
        ]);
       
    }

    //SUPPRESSION OBJECTIFS
    #[Route('/objectif/{id}/delete', name: 'objectif_delete')]
    public function delete(string $id, EntityManagerInterface $entityManager, TaskPersoRepository $taskPersoRepository): Response
    {
        $user = $this->security->getUser();
        $taskPerso = $taskPersoRepository->findOneBy(['user' => $user, 'Task' => $id]);
        $entityManager->remove($taskPerso);
        if ($taskPerso->getTask()->getPerso() == true){//si l'objectif est perso, on supprime la tâche associée
            $entityManager->remove($taskPerso->getTask());
        }
        $entityManager->flush();
        return $this->redirect('/home/objectifs?voir=tous?delete=ok');
    }

} 
