<?php

namespace App\Controller\DanceSchool;

use App\Repository\DanceSchoolRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dashboard/danceschool/{schoolId}/users')]
class UserController extends AbstractController
{
    #[Route('/', name: 'danceschool_users_index', methods: ['GET'])]
    public function index(int $schoolId, DanceSchoolRepository $repo): Response
    {
        $school = $repo->find($schoolId);

        return $this->render('danceSchool/user/index.html.twig', [
            'school' => $school,
            'users' => $school->getAllowedUser(),
            'userType' => 'User',
            'deleteRoute' => 'danceschool_users_delete',
            'addRoute' => 'danceschool_users_add',
            'indexRoute' => 'danceschool_users_index'
        ]);
    }

    #[Route('/delete/{userId}', name: 'danceschool_users_delete', methods: ['GET'])]
    public function delete(int $schoolId, int $userId, DanceSchoolRepository  $repo, UserRepository $userRepo, EntityManagerInterface $em): Response
    {

        $school = $repo->find($schoolId);
        $user = $userRepo->find($userId);

        $school->removeAllowedUser($user);
        $em->flush();

        return $this->redirectToRoute('danceschool_users_index', ['schoolId' => $schoolId]);
    }

    #[Route('/add', name: 'danceschool_users_add', methods: ['POST'])]
    public function addAdmin(int $schoolId, Request $request, DanceSchoolRepository $repo, UserRepository $userRepo, EntityManagerInterface $em): Response
    {
        $school = $repo->find($schoolId);

        if (!$this->isCsrfTokenValid('add_user', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $email = $request->request->get('email');
        $user = $userRepo->findOneBy(['email' => $email]);

        if (!$user) {
            $this->addFlash('danger', 'User not found!');
            return $this->redirectToRoute('danceschool_users_index', ['schoolId' => $schoolId]);
        }

        $school->addAllowedUser($user);
        $em->flush();

        return $this->redirectToRoute('danceschool_users_index', ['schoolId' => $schoolId]);
    }
}
