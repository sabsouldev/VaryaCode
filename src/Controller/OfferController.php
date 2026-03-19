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
        return $this->render('offer/index.html.twig');
    }
}
