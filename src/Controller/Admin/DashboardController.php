<?php

namespace App\Controller\Admin;


use App\Entity\DevisRequest;
use App\Repository\DevisRequestRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Admin\ProjectCrudController;
use App\Controller\Admin\TestimonialCrudController;
use App\Controller\Admin\DevisRequestCrudController;
use App\Controller\Admin\ContactMessageCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;


#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(private DevisRequestRepository $devisRepository)
    {
    }

    
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
        yield MenuItem::linkTo(ProjectCrudController::class, 'Portfolio', 'fa fa-images')->setAction(Action::INDEX);
        yield MenuItem::linkTo(TestimonialCrudController::class, 'Témoignages', 'fa fa-quote-right')->setAction(Action::INDEX);
        yield MenuItem::section('Demandes');
        yield MenuItem::linkTo(DevisRequestCrudController::class, 'Demandes de devis', 'fa fa-file-invoice')->setAction(Action::INDEX); 
        yield MenuItem::linkTo(ContactMessageCrudController::class, 'Messages', 'fa fa-envelope')->setAction(Action::INDEX);
        yield MenuItem::section('');
        yield MenuItem::linkToRoute('Voir le site', 'fa fa-external-link-alt', 'app_home');
        yield MenuItem::linkToLogout('Déconnexion', 'fa fa-sign-out-alt');
    }
}
