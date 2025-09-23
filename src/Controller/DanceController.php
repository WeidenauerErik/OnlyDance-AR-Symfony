<?php

namespace App\Controller;

use App\Entity\Dance;
use App\Form\DanceType;
use App\Repository\DanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dance')]
final class DanceController extends AbstractController
{
    #[Route(name: 'app_dance_index', methods: ['GET'])]
    public function index(DanceRepository $danceRepository): Response
    {
        return $this->render('dance/index.html.twig', [
            'dances' => $danceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_dance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dance = new Dance();
        $form = $this->createForm(DanceType::class, $dance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dance);
            $entityManager->flush();

            return $this->redirectToRoute('app_dance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dance/new.html.twig', [
            'dance' => $dance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dance_show', methods: ['GET'])]
    public function show(Dance $dance): Response
    {
        return $this->render('dance/show.html.twig', [
            'dance' => $dance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Dance $dance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DanceType::class, $dance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dance/edit.html.twig', [
            'dance' => $dance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dance_delete', methods: ['POST'])]
    public function delete(Request $request, Dance $dance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dance->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($dance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dance_index', [], Response::HTTP_SEE_OTHER);
    }
}
