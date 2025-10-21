<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\DanceRepository;
use App\Repository\StepsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('api')]
final class BackendController extends AbstractController
{
    #[Route('/getFiveDances', name: 'app_mainMenu_getFiveDances', methods: ['GET'])]
    public function getFiveDances(DanceRepository $danceRepository): JsonResponse
    {
        $dances = $danceRepository->findBy([], ['id' => 'ASC'], 5);

        $data = array_map(fn($dance) => [
            'id' => $dance->getId(),
            'name' => $dance->getName(),
        ], $dances);

        return new JsonResponse($data, 200);
    }

    #[Route('/getAllDances', name: 'app_mainMenu_getAllDances', methods: ['GET'])]
    public function getAllDances(DanceRepository $danceRepository): JsonResponse
    {
        $dances = $danceRepository->findAll();

        $data = array_map(fn($dance) => [
            'id' => $dance->getId(),
            'name' => $dance->getName(),
        ], $dances);

        return new JsonResponse($data, 200);
    }

    #[Route('/getDanceById/{danceId}', name: 'dance_animator_getDanceById', methods: ['GET'])]
    public function getDanceById(int $danceId, StepsRepository $stepsRepository): JsonResponse
    {
        if ($danceId <= 0) {
            return new JsonResponse(['success' => false, 'error' => 'Ungültige Dance-ID!'], 400);
        }

        $steps = $stepsRepository->findBy(['dance_id' => $danceId]);

        if (!$steps) {
            return new JsonResponse(['success' => false, 'error' => 'Keine Schritte für diesen Tanz gefunden!'], 404);
        }

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

    #[Route('/login', name: 'login_user', methods: ['POST'])]
    public function loginUser(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || empty($data['email']) || empty($data['password'])) {
            return new JsonResponse(['success' => false, 'error' => 'E-Mail oder Passwort fehlt!'], 400);
        }

        $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($data['password']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['success' => false, 'error' => 'Ungültiges E-Mail-Format!'], 400);
        }

        $user = $userRepository->findOneBy(['email' => $email]);
        if (!$user) {
            return new JsonResponse(['success' => false, 'error' => 'E-Mail oder Passwort ist falsch!'], 401);
        }

        if (!$passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse(['success' => false, 'error' => 'E-Mail oder Passwort ist falsch!'], 401);
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Login erfolgreich!',
            'password' => $user->getPassword(),
        ], 200);
    }

    #[Route('/register', name: 'register_user', methods: ['POST'])]
    public function registerUser(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || empty($data['email']) || empty($data['password'])) {
            return new JsonResponse(['success' => false, 'error' => 'E-Mail und Passwort sind erforderlich!'], 400);
        }

        $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($data['password']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['success' => false, 'error' => 'Ungültiges E-Mail-Format!'], 400);
        }

        if (strlen($password) < 6) {
            return new JsonResponse(['success' => false, 'error' => 'Passwort muss mindestens 6 Zeichen lang sein!'], 400);
        }

        $existing = $em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existing) {
            return new JsonResponse(['success' => false, 'error' => 'Benutzer existiert bereits!'], 409);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($passwordHasher->hashPassword($user, $password));

        $em->persist($user);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'message' => 'Benutzer erfolgreich registriert!',
            'password' => $user->getPassword(),
        ], 201);
    }

    #[Route('/checkUser', name: 'check_user', methods: ['POST'])]
    public function checkUser(Request $request, UserRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || empty($data['email']) || empty($data['password'])) {
            return new JsonResponse(['success' => false, 'error' => 'E-Mail oder Passwort fehlt!'], 400);
        }

        $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($data['password']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['success' => false, 'error' => 'Ungültiges E-Mail-Format!'], 400);
        }

        $user = $userRepository->findOneBy(['email' => $email]);
        if (!$user) {
            return new JsonResponse(['success' => false, 'error' => 'Benutzer nicht gefunden!'], 404);
        }

        if (!password_verify($password, $user->getPassword()) && $user->getPassword() !== $password) {
            return new JsonResponse(['success' => false, 'error' => 'Falsches Passwort!'], 401);
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Benutzer erfolgreich verifiziert!',
        ], 200);
    }
}
