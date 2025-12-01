<?php

namespace App\Controller\DanceSchool;

use App\Repository\DanceSchoolRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('dashboard/danceschool/{schoolId}/users')]
class UserController extends AbstractController
{
    #[Route('/', name: 'danceschool_users_index', methods: ['GET'])]
    public function index(int $schoolId, DanceSchoolRepository $repo): Response
    {
        $school = $repo->find($schoolId);

        return $this->render('danceSchool/user/index.html.twig', [
            'school' => $school,
            'users' => $school->getAllowedUser(),
            'userType' => 'User'
        ]);
    }

    #[Route('/delete/{userId}', name: 'danceschool_users_delete', methods: ['GET'])]
    public function delete(
        int                    $schoolId,
        int                    $userId,
        DanceSchoolRepository  $repo,
        UserRepository         $userRepo,
        EntityManagerInterface $em
    ): Response
    {

        $school = $repo->find($schoolId);
        $user = $userRepo->find($userId);

        $school->removeAllowedUser($user);
        $em->flush();

        return $this->redirectToRoute('danceschool_users_index', ['schoolId' => $schoolId]);
    }

}
