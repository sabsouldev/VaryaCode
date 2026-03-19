<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PortfolioController extends AbstractController
{
    #[Route('/portfolio', name: 'app_portfolio')]
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('portfolio/index.html.twig', [
            'projects' => $projectRepository->findPublished(),
        ]);
    }
}
