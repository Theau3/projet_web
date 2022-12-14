<?php

namespace App\Controller;

use App\Entity\TaskPerso;
use App\Form\Type\TaskPersoType;
use App\Repository\TaskPersoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/task_perso')]
class AdminTaskPersoController extends AbstractController
{
    #[Route('/', name: 'app_admin_task_perso_index', methods: ['GET'])]
    public function index(TaskPersoRepository $taskPersoRepository): Response
    {
        return $this->render('admin_task_perso/index.html.twig', [
            'task_persos' => $taskPersoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_task_perso_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TaskPersoRepository $taskPersoRepository): Response
    {
        $taskPerso = new TaskPerso();
        $form = $this->createForm(TaskPersoType::class, $taskPerso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskPersoRepository->save($taskPerso, true);

            return $this->redirectToRoute('app_admin_task_perso_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_task_perso/new.html.twig', [
            'task_perso' => $taskPerso,
            'form' => $form,
        ]);
    }

    #[Route('/{Task}', name: 'app_admin_task_perso_show', methods: ['GET'])]
    public function show(TaskPerso $taskPerso): Response
    {
        return $this->render('admin_task_perso/show.html.twig', [
            'task_perso' => $taskPerso,
        ]);
    }

    #[Route('/{Task}/edit', name: 'app_admin_task_perso_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TaskPerso $taskPerso, TaskPersoRepository $taskPersoRepository): Response
    {
        $form = $this->createForm(TaskPersoType::class, $taskPerso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskPersoRepository->save($taskPerso, true);

            return $this->redirectToRoute('app_admin_task_perso_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_task_perso/edit.html.twig', [
            'task_perso' => $taskPerso,
            'form' => $form,
        ]);
    }

    #[Route('/{Task}', name: 'app_admin_task_perso_delete', methods: ['POST'])]
    public function delete(Request $request, TaskPerso $taskPerso, TaskPersoRepository $taskPersoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$taskPerso->getTask(), $request->request->get('_token'))) {
            $taskPersoRepository->remove($taskPerso, true);
        }

        return $this->redirectToRoute('app_admin_task_perso_index', [], Response::HTTP_SEE_OTHER);
    }
}
