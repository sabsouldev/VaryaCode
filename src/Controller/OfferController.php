<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OfferController extends AbstractController
{
   #[Route('/offres', name: 'app_offers')]
public function index(): Response
{
    return $this->render('offer/index.html.twig', [
        'stripe_vitrine'          => $_ENV['STRIPE_LINK_VITRINE'] ?? '#',
        'stripe_vitrine_libre'    => $_ENV['STRIPE_LINK_VITRINE_LIBRE'] ?? '#',
        'stripe_pilote'           => $_ENV['STRIPE_LINK_PILOTE'] ?? '#',
        'stripe_pilote_libre'     => $_ENV['STRIPE_LINK_PILOTE_LIBRE'] ?? '#',
        'stripe_decollage'        => $_ENV['STRIPE_LINK_DECOLLAGE'] ?? '#',
        'stripe_decollage_libre'  => $_ENV['STRIPE_LINK_DECOLLAGE_LIBRE'] ?? '#',
        'stripe_maintenance'      => $_ENV['STRIPE_LINK_MAINTENANCE'] ?? '#',
    ]);
}
}
