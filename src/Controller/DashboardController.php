<?php
// src/Controller/AdminController.php
namespace App\Controller;

use App\Repository\DanceSchoolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard/admin', name: 'admin_dashboard')]
    public function adminDashboard(): Response
    {
        return $this->render('admin/admin.html.twig');
    }

    #[Route('/dashboard/dance_school', name: 'danceSchool_dashboard')]
    public function danceSchoolDashboard(): Response
    {
        $user = $this->getUser();

        $adminSchools = $user->getAdminDanceSchools();
        return $this->render('danceSchool/danceSchool.html.twig', [
            'adminSchools' => $adminSchools,
        ]);
    }

    #[Route('/dashboard/dance_school/{id}', name: 'app_danceschool_selected')]
    public function danceSchoolSelectedDashboard(int $id, DanceSchoolRepository $repo): Response
    {
        $school = $repo->find($id);

        if (!$school) {
            throw $this->createNotFoundException("DanceSchool not found");
        }

        return $this->render('danceSchool/danceSchoolSelected.html.twig', [
            'school' => $school,
        ]);
    }
}
