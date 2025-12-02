<?php

namespace App\Controller\Unity;

use App\Repository\DanceRepository;
use App\Repository\StepsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api')]
final class DanceManageController extends AbstractController
{
    #[Route('/getFiveDances/{danceSchoolId}', name: 'app_mainMenu_getFiveDances', methods: ['GET'])]
    public function getFiveDances(int $danceSchoolId, DanceRepository $danceRepository): JsonResponse
    {
        $dances = $danceRepository->findBy(['owner' => $danceSchoolId], ['id' => 'ASC'], 5);

        $data = array_map(fn($dance) => [
            'id' => $dance->getId(),
            'name' => $dance->getName(),
        ], $dances);

        return new JsonResponse($data, 200);
    }

    #[Route('/getAllDances/{danceSchoolId}', name: 'app_mainMenu_getAllDances', methods: ['GET'])]
    public function getAllDances(int $danceSchoolId, DanceRepository $danceRepository): JsonResponse
    {
        $dances = $danceRepository->findBy(['owner' => $danceSchoolId], ['id' => 'ASC']);

        $data = array_map(fn($dance) => [
            'id' => $dance->getId(),
            'name' => $dance->getName(),
        ], $dances);

        return new JsonResponse($data, 200);
    }

    #[Route('/getDanceById/{danceId}', name: 'dance_animator_getDanceById', methods: ['GET'])]
    public function getDanceById(int $danceId, StepsRepository $stepsRepository): JsonResponse
    {
        if ($danceId <= 0) return new JsonResponse(['success' => false, 'error' => 'Ungültige Dance-ID!'], 400);
        $steps = $stepsRepository->findBy(['dance_id' => $danceId]);

        if (!$steps) return new JsonResponse(['success' => false, 'error' => 'Keine Schritte für diesen Tanz gefunden!'], 404);

        $data = array_map(fn($step) => [
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
        ], $steps);

        return new JsonResponse(['success' => true, 'data' => $data], 200);
    }

    #[Route('/getUserDanceSchoolsByEmail/{email}', name: 'app_user_getDanceSchoolsByEmail', methods: ['GET'])]
    public function getUserDanceSchoolsByEmail(string $email, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->findOneBy(['email' => $email]);
        if (!$user) return new JsonResponse(['success' => false, 'error' => 'User nicht gefunden'], 404);

        $danceSchools = $user->getDanceSchools();

        $data = array_map(fn($school) => [
            'id' => $school->getId(),
            'name' => $school->getName(),
        ], $danceSchools->toArray());

        return new JsonResponse(['success' => true, 'data' => $data], 200);
    }
}
