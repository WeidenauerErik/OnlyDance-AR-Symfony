<?php

namespace App\Controller;

use App\Entity\Steps;
use App\Form\StepsType;
use App\Repository\StepsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('steps')]
final class StepsController extends AbstractController
{
    #[Route(name: 'app_steps_index', methods: ['GET'])]
    public function index(StepsRepository $stepsRepository): Response
    {
        return $this->render('steps/index.html.twig', [
            'steps' => $stepsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_steps_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $step = new Steps();
        $form = $this->createForm(StepsType::class, $step);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($step);
            $entityManager->flush();

            return $this->redirectToRoute('app_steps_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('steps/new.html.twig', [
            'step' => $step,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_steps_show', methods: ['GET'])]
    public function show(Steps $step): Response
    {
        return $this->render('steps/show.html.twig', [
            'step' => $step,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_steps_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Steps $step, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StepsType::class, $step);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_steps_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('steps/edit.html.twig', [
            'step' => $step,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_steps_delete', methods: ['POST'])]
    public function delete(Request $request, Steps $step, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$step->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($step);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_steps_index', [], Response::HTTP_SEE_OTHER);
    }
}
