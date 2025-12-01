<?php

namespace App\Controller\Website;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WebsiteController extends AbstractController
{
    #[Route('/', name: 'app_frontend')]
    public function index(): Response
    {
        return $this->render('frontend/index.html.twig');
    }
}
