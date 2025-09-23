<?php

namespace App\Controller;

use App\Repository\DanceRepository;
use App\Repository\StepsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class UnityAppController extends AbstractController
{
    #[Route('/mainMenu/getAllDances', name: 'app_mainMenu_getAllDances', methods: ['GET'])]
    public function getAllDances(DanceRepository $danceRepository): JsonResponse
    {
        $dances = $danceRepository->findBy([], ['id' => 'ASC'], 5);

        $data = array_map(function($dance) {
            return [
                'id' => $dance->getId(),
                'name' => $dance->getName(),
            ];
        }, $dances);

        return new JsonResponse($data);
    }

#[Route('/danceAnimator/getDanceById/{danceId}', name: 'dance_animator_getDanceById', methods: ['GET'])]
public function getDanceById(int $danceId, StepsRepository $stepsRepository): JsonResponse
{
    $steps = $stepsRepository->findBy(['dance_id' => $danceId]);

    $data = array_map(function($step) {
        return [
            'id' => $step->getId(),
            'm1_x' => $step->getM1X(),
            'm1_y' => $step->getM1Y(),
            'm1_toe' => $step->isM1Toe(),
            'm1_heel' => $step->isM1Heel(),
            'm1_rotate' => $step->getM1Rotate(),
            'm2_x' => $step->getM2X(),
            'm2_y' => $step->getM2Y(),
            'm2_toe' => $step->isM2Toe(),
            'm2_heel' => $step->isM2Heel(),
            'm2_rotate' => $step->getM2Rotate(),
        ];
    }, $steps);

    return new JsonResponse($data);
}
}
