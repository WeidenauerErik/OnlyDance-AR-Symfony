<?php
// src/Controller/AdminController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard/admin', name: 'admin_dashboard')]
    public function adminDashboard(): Response
    {
        return $this->render('main/admin.html.twig');
    }

    #[Route('/dashboard/dance_school', name: 'danceSchool_dashboard')]
    public function danceSchoolDashboard(): Response
    {
        return $this->render('main/danceSchool.html.twig');
    }

    #[Route('/no_rights', name: 'no_rights')]
    public function noRightsErrorSite(): Response
    {
        return $this->render('main/noRights.html.twig');
    }

}
