<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AboutController extends AbstractController
{
    #[Route('/a-propos', name: 'app_about')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_home', [], Response::HTTP_MOVED_PERMANENTLY);
    }
}
