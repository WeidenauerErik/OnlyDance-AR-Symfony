<?php

namespace App\Controller\Unity;
use App\Controller\Admin\UserController;
use App\Entity\DanceSchool;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api')]
final class UserManageController extends AbstractController
{
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

        $school = $em->getRepository(DanceSchool::class)->findOneBy(['name' => 'OnlyDance']);
        $user->addDanceSchool($school);

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

    #[Route('/changePassword', name: 'change_user_password', methods: ['POST'])]
    public function changePassword(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!$data || empty($data['email']) || empty($data['oldPassword']) || empty($data['newPassword'])) {
            return new JsonResponse(['success' => false, 'error' => 'E-Mail, altes Passwort und neues Passwort sind erforderlich!'], 400);
        }

        $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
        $oldPassword = trim($data['oldPassword']);
        $newPassword = trim($data['newPassword']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['success' => false, 'error' => 'Ungültiges E-Mail-Format!'], 400);
        }

        $user = $userRepository->findOneBy(['email' => $email]);
        if (!$user) {
            return new JsonResponse(['success' => false, 'error' => 'Benutzer nicht gefunden!'], 404);
        }

        if (!$passwordHasher->isPasswordValid($user, $oldPassword)) {
            return new JsonResponse(['success' => false, 'error' => 'Altes Passwort ist falsch!'], 401);
        }

        if (strlen($newPassword) < 6) {
            return new JsonResponse(['success' => false, 'error' => 'Neues Passwort muss mindestens 6 Zeichen lang sein!'], 400);
        }

        $hashedNewPassword = $passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hashedNewPassword);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'message' => 'Passwort erfolgreich geändert!'
        ], 200);
    }

    #[Route('/deleteAccount', name: 'delete_user_account', methods: ['POST'])]
    public function deleteAccount(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!$data || empty($data['email']) || empty($data['password'])) {
            return new JsonResponse(['success' => false, 'error' => 'E-Mail und Passwort sind erforderlich!'], 400);
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

        if (!$passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse(['success' => false, 'error' => 'Falsches Passwort!'], 401);
        }

        $em->remove($user);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'message' => 'Dein Konto wurde erfolgreich gelöscht!'
        ], 200);
    }

}
