<?php

namespace App\Controller\Admin;

use App\Entity\ContactMessage;
use App\Entity\DevisRequest;
use App\Entity\Project;
use App\Entity\Testimonial;
use App\Repository\DevisRequestRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(private DevisRequestRepository $devisRepository)
    {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'devis_count_month' => $this->devisRepository->countThisMonth(),
            'most_requested_offer' => $this->devisRepository->getMostRequestedOffer(),
            'total_devis' => $this->devisRepository->count([]),
            'new_devis' => $this->devisRepository->count(['status' => DevisRequest::STATUS_NEW]),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('VaryaCode Admin')
            ->setFaviconPath('favicon.ico');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::section('Contenu');
        yield MenuItem::linkToCrud('Portfolio', 'fa fa-images', Project::class);
        yield MenuItem::linkToCrud('Témoignages', 'fa fa-quote-right', Testimonial::class);
        yield MenuItem::section('Demandes');
        yield MenuItem::linkToCrud('Demandes de devis', 'fa fa-file-invoice', DevisRequest::class);
        yield MenuItem::linkToCrud('Messages', 'fa fa-envelope', ContactMessage::class);
        yield MenuItem::section('');
        yield MenuItem::linkToRoute('Voir le site', 'fa fa-external-link-alt', 'app_home');
        yield MenuItem::linkToLogout('Déconnexion', 'fa fa-sign-out-alt');
    }
}
