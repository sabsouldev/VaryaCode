<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DiscoverController extends AbstractController
{
    #[Route('/decouvrir', name: 'app_decouvrir')]
    public function index(): Response
    {
        return $this->render('discover/index.html.twig');
    }

    #[Route('/decouvrir/resultat', name: 'app_decouvrir_resultat')]
    public function resultat(Request $request): Response
    {
        $session = $request->getSession();
        $answers = $session->get('discover_answers', []);

        $plan = $this->recommend($answers);

        return $this->render('discover/resultat.html.twig', [
            'plan' => $plan,
            'answers' => $answers,
        ]);
    }

    #[Route('/decouvrir/submit', name: 'app_decouvrir_submit', methods: ['POST'])]
    public function submit(Request $request): Response
    {
        $data = $request->request->all();
        $request->getSession()->set('discover_answers', $data);

        return $this->redirectToRoute('app_decouvrir_resultat');
    }

    #[Route('/merci', name: 'app_merci')]
    public function merci(): Response
    {
        return $this->render('discover/merci.html.twig');
    }

    private function recommend(array $answers): array
    {
        $plans = [
            'vitrine' => [
                'name' => 'Vitrine',
                'price' => 35,
                'tagline' => 'On vous met en ligne',
                'stripe_engagement' => '#', // TODO: Stripe Payment Link
                'stripe_libre' => '#',
            ],
            'pilote' => [
                'name' => 'Pilote Automatique',
                'price' => 55,
                'tagline' => 'Vos clients réservent en ligne',
                'stripe_engagement' => '#',
                'stripe_libre' => '#',
            ],
            'decollage' => [
                'name' => 'Décollage',
                'price' => 75,
                'tagline' => 'Votre site travaille pour vous',
                'stripe_engagement' => '#',
                'stripe_libre' => '#',
            ],
            'oneshot' => [
                'name' => 'Projet sur devis',
                'price' => 0,
                'tagline' => 'Paiement en une fois, sur devis',
                'stripe_engagement' => null,
                'stripe_libre' => null,
            ],
        ];

        $budget = $answers['budget'] ?? '';
        $needs = $answers['needs'] ?? [];
        if (is_string($needs)) {
            $needs = [$needs];
        }

        // "Payer en une fois" → one-shot
        if ($budget === 'oneshot') {
            return $plans['oneshot'];
        }

        // Budget > 60€ OR needs include blog/fidélité → Décollage
        if ($budget === 'plus60' || array_intersect(['blog', 'fidelisation'], $needs)) {
            return $plans['decollage'];
        }

        // Budget 40-60€ OR needs include reservation/avis → Pilote Automatique
        if ($budget === '40-60' || array_intersect(['reservation', 'avis'], $needs)) {
            return $plans['pilote'];
        }

        // Default → Vitrine
        return $plans['vitrine'];
    }
}
