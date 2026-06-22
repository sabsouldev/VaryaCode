<?php
namespace App\Controller;
use App\Repository\TestimonialRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        TestimonialRepository $testimonialRepository,
        ProjectRepository $projectRepository
    ): Response {
        return $this->render('home/index.html.twig', [
            'testimonials' => $testimonialRepository->findPublished(),
            'projects' => $projectRepository->findPublished(),
        ]);
    }
}
