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
use App\Repository\TaskRepository;
use App\Repository\CategoryRepository;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
    public function objectifs(CategoryRepository $categoryRepository): Response
    {
        $url = $_SERVER['REQUEST_URI'];
        $categories = $categoryRepository->findAll();
        return $this->render('objectifs.html.twig',[
                                'url' => $url,
                                'categories' => $categories
    ]);
    }

    #[Route('/tous', name: 'liste_show')]
    public function showListe(TaskPersoRepository $taskPersoRepository, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $user = $this->security->getUser();
        $show_done = $session->get('show_done');


        if ($show_done) {
            // afficher les tâches terminées
            $objectifs_non_verifies = $taskPersoRepository->findBy(['user' => $user, 'isDone' => true]);
          } else {
            // afficher les tâches non terminées
            $objectifs_non_verifies = $taskPersoRepository->findBy(['user' => $user, 'isDone' => false]);
          }

       if (isset($_GET['tri'])){
            $tri = $_GET['tri'];
            $objectifs = [];
            if ($tri == '1'){
            foreach($objectifs_non_verifies as $objectif){
                if($objectif->getIsFavoris() == 1 ){
                    array_push($objectifs, $objectif); //On ne veut que les objectifs non validés
                }
            }
        } else {
            foreach($objectifs_non_verifies as $objectif){
                if($objectif->getTask()->getCategory()->getIdCat() == $tri ){
                    array_push($objectifs, $objectif); 
                }
            }
        }}else {
            $objectifs = $objectifs_non_verifies;
        }
    
        if (!$objectifs) {
            $objectifs = [];
        }
    
        return $this->render('task/liste.html.twig', ['objectifs' => $objectifs, 
                                                      'entityManager' => $entityManager,
                                                      'show_done' => $show_done]);
    }


    #[Route('/objectif/{id}', name: 'objectif_show')]
    public function show(string $id, TaskPersoRepository $taskPersoRepository): Response
    {
        $user = $this->security->getUser();
        $objectifs_non_verifies = $taskPersoRepository->findBy(['user' => $user]);
        $objectifs = [];
        foreach($objectifs_non_verifies as $objectif){
            if($objectif->getIsDone() == false){
                array_push($objectifs, $objectif); //On ne veut que les objectifs non validés
            }
        }
        $objectif = $taskPersoRepository->findOneBy(['user' => $user, 'Task' => $id]);
        $index = array_search($objectif, $objectifs);//mettre l'index

        if (!$objectif) {
            throw $this->createNotFoundException(
                'Aucun objectif existe avec cet id : '.$id
            );
        }

        //calculs des dates
        $dateDebut = $objectif->getDateDebut()->format('Y-m-d');
        $dateFin = $objectif->getDueDate()->format('Y-m-d');
        $aujourdhui = new \DateTime();
        $dateIntervalleDebut = $objectif->getDateDebut()->diff($aujourdhui);
        $nombreJoursEcoules = $dateIntervalleDebut->days;
        $nombreMoisEcoules = $dateIntervalleDebut->m;
        $nombreAnneesEcoules = $dateIntervalleDebut->y;
        $dateIntervalleFin = $objectif->getDueDate()->diff($aujourdhui);
        $nombreJoursRestants = $dateIntervalleFin->days;
        $nombreMoisRestants = $dateIntervalleFin->m;
        $nombreAnneesRestants = $dateIntervalleFin->y;



            return $this->render('task/objectif.html.twig',['objectif' => $objectif, 
                                                            'objectifs' => $objectifs, 
                                                            'index' => $index, 
                                                            'dateDebut' => $dateDebut, 
                                                            'dateFin' => $dateFin,
                                                            'nombreJoursEcoules' => $nombreJoursEcoules,
                                                            'nombreMoisEcoules' => $nombreMoisEcoules,
                                                            'nombreAnneesEcoules' => $nombreAnneesEcoules,
                                                            'nombreJoursRestants' => $nombreJoursRestants,
                                                            'nombreMoisRestants' => $nombreMoisRestants,
                                                            'nombreAnneesRestants' => $nombreAnneesRestants,
                                                            'aujourdhui' => $aujourdhui,
                                                            ]);
    }

    #[Route('/objectif/{id}/valider', name: 'valider_objectif')]
    public function valider($id, EntityManagerInterface $entityManager, TaskPersoRepository $taskPersoRepository, Request $request): Response
    {
        $user = $this->security->getUser();
        $objectif = $taskPersoRepository->findOneBy(['user' => $user, 'Task' => $id]);

        if($objectif->getRepetition()+1 == $objectif->getTask()->getAvancement() || $objectif->getRepetition() >= $objectif->getTask()->getAvancement()){
            $objectif->setIsDone(true);
            //Voir si on peut mettre l'xp en fonction de la difficulté
            $xp = $objectif->getRepetition(); //Si on finit l'objectif on gagne autant d'xp que de répétitions en bonus
            $user->setProgression($user->getProgression()+$xp);
        }
        if($objectif->getIsDone() == false) {
        $objectif->setRepetition($objectif->getRepetition()+1);
        $xp = 10;
        $user->setProgression($user->getProgression()+$xp);
        }
        $entityManager->flush();

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
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
    public function edit(Request $request,string $id, TaskPersoRepository $taskPersoRepository): Response
    {
        $user = $this->security->getUser();
        $taskPerso = $taskPersoRepository->findOneBy(['user' => $user, 'Task' => $id]);
        $form = $this->createForm(TaskPersoType::class, $taskPerso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskPerso = $form->getData();
            $task = $taskPerso->getTask();
            $dateDebut = $taskPerso->getDateDebut();
            $dateFin = $taskPerso->getDueDate();
            $dateIntervalle = $dateDebut->diff($dateFin);
            $nombreJours = $dateIntervalle->days;
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
            $taskPersoRepository->save($taskPerso, true);
            $url = $_SERVER['REQUEST_URI'];
            return $this->redirect($url);
        }

        return $this->render('task/edit.html.twig', [
            'task_perso' => $form->createView(),
        ]);
       
    }

    //SUPPRESSION OBJECTIFS
    #[Route('/objectif/{id}/delete', name: 'objectif_delete')]
    public function delete(string $id, EntityManagerInterface $entityManager, TaskPersoRepository $taskPersoRepository, Request $request): Response
    {
        $user = $this->security->getUser();
        $taskPerso = $taskPersoRepository->findOneBy(['user' => $user, 'Task' => $id]);
        $entityManager->remove($taskPerso);
        if ($taskPerso->getTask()->getPerso() == true){//si l'objectif est perso, on supprime la tâche associée
            $entityManager->remove($taskPerso->getTask());
        }
        $entityManager->flush();
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    //AJOUTER AUX FAVORIS
    #[Route('/objectif/{id}/favoris', name: 'objectif_favoris')]
    public function favoris(string $id, EntityManagerInterface $entityManager, TaskPersoRepository $taskPersoRepository, Request $request): Response
    {
        $user = $this->security->getUser();
        $taskPerso = $taskPersoRepository->findOneBy(['user' => $user, 'Task' => $id]);
        $taskPerso->setIsFavoris(true);
        $entityManager->flush();
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    //SUPPRIMER DES FAVORIS
    #[Route('/objectif/{id}/favoris/delete', name: 'objectif_favoris_delete')]
    public function favorisDelete(string $id, EntityManagerInterface $entityManager, TaskPersoRepository $taskPersoRepository, Request $request): Response
    {
        $user = $this->security->getUser();
        $taskPerso = $taskPersoRepository->findOneBy(['user' => $user, 'Task' => $id]);
        $taskPerso->setIsFavoris(false);
        $entityManager->flush();
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    //CONTINUER UN OBJECTIF
    #[Route('/objectif/{id}/continuer', name: 'continuer_objectif')]
    public function continuer(string $id, EntityManagerInterface $entityManager, TaskPersoRepository $taskPersoRepository, Request $request): Response
    {
        $user = $this->security->getUser();
        $taskPerso = $taskPersoRepository->findOneBy(['user' => $user, 'Task' => $id]);
        $taskPerso->setIsDone(false);
        $entityManager->flush();
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }


} 
