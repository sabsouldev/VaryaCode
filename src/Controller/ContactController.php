<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Entity\DevisRequest;
use App\Service\OfferSuggestionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {
        return $this->render('contact/index.html.twig', [
            'contact_success' => $request->query->get('success') === '1',
        ]);
    }

    #[Route('/contact/devis', name: 'app_devis_submit', methods: ['POST'])]
    public function submitDevis(
        Request $request,
        EntityManagerInterface $em,
        OfferSuggestionService $suggestionService,
        MailerInterface $mailer,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!$data || empty($data['contactName']) || empty($data['contactEmail'])) {
            return new JsonResponse(['success' => false, 'message' => 'Données manquantes.'], 400);
        }

        $suggestedOffer = $suggestionService->suggest($data);

        $devis = new DevisRequest();
        $devis->setContactName($data['contactName']);
        $devis->setContactEmail($data['contactEmail']);
        $devis->setContactPhone($data['contactPhone'] ?? null);
        $devis->setStructureType($data['structureType'] ?? '');
        $devis->setHasExistingSite(($data['hasExistingSite'] ?? '0') === '1');
        $devis->setMainObjective($data['mainObjective'] ?? '');
        $devis->setEstimatedPages($data['estimatedPages'] ?? '');
        $devis->setNeedsAutonomy($data['needsAutonomy'] ?? 'ne_sais_pas');
        $devis->setFeatures($data['features'] ?? []);
        $devis->setBudget($data['budget'] ?? '');
        $devis->setTimeline($data['timeline'] ?? '');
        $devis->setAdditionalMessage($data['additionalMessage'] ?? null);
        $devis->setSuggestedOffer($suggestedOffer);

        $em->persist($devis);
        $em->flush();

        // Email to admin
        try {
            $adminEmail = (new Email())
                ->from('noreply@varyacode.fr')
                ->to('contact@varyacode.fr')
                ->subject('Nouvelle demande de devis — ' . $data['contactName'])
                ->html($this->renderView('emails/devis_admin.html.twig', [
                    'devis' => $devis,
                ]));
            $mailer->send($adminEmail);

            // Confirmation to visitor
            $confirmEmail = (new Email())
                ->from('contact@varyacode.fr')
                ->to($data['contactEmail'])
                ->subject('VaryaCode — Votre demande a bien été reçue')
                ->html($this->renderView('emails/devis_confirmation.html.twig', [
                    'devis' => $devis,
                ]));
            $mailer->send($confirmEmail);
        } catch (\Exception $e) {
            // Log but don't fail — the devis is already saved
        }

        return new JsonResponse([
            'success' => true,
            'suggestedOffer' => $suggestedOffer,
        ]);
    }

    #[Route('/contact/message', name: 'app_contact_submit', methods: ['POST'])]
    public function submitContact(
        Request $request,
        EntityManagerInterface $em,
        MailerInterface $mailer,
    ): Response {
        $name = $request->request->get('contact_name', '');
        $email = $request->request->get('contact_email', '');
        $subject = $request->request->get('contact_subject', '');
        $messageText = $request->request->get('contact_message', '');

        if (empty($name) || empty($email) || empty($subject) || empty($messageText)) {
            $this->addFlash('error', 'Veuillez remplir tous les champs obligatoires.');
            return $this->redirectToRoute('app_contact');
        }

        $contact = new ContactMessage();
        $contact->setName($name);
        $contact->setEmail($email);
        $contact->setSubject($subject);
        $contact->setMessage($messageText);

        $em->persist($contact);
        $em->flush();

        try {
            $adminEmail = (new Email())
                ->from('noreply@varyacode.fr')
                ->to('contact@varyacode.fr')
                ->subject('Nouveau message — ' . $subject)
                ->html($this->renderView('emails/contact_admin.html.twig', [
                    'contact' => $contact,
                ]));
            $mailer->send($adminEmail);
        } catch (\Exception $e) {
            // Log but don't fail
        }

        return $this->redirectToRoute('app_contact', ['success' => '1']);
    }
}
