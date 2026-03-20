<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        #[\Symfony\Component\DependencyInjection\Attribute\Autowire(env: 'ADMIN_EMAIL')]
        private string $adminEmail,
        #[\Symfony\Component\DependencyInjection\Attribute\Autowire(env: 'ADMIN_PASSWORD')]
        private string $adminPassword,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Admin user
        $admin = new User();
        $admin->setEmail($this->adminEmail);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, $this->adminPassword));
        $manager->persist($admin);

        // Projects
        $projects = [
            [
                'title' => 'Les Univers Singuliers (LUSgem)',
                'slug' => 'lusgem',
                'shortDescription' => 'Site institutionnel complet pour une structure associative locale.',
                'description' => 'Site institutionnel complet pour une structure associative locale. Gestion des contenus, interface d\'administration sur mesure, architecture pensée pour évoluer avec la structure.',
                'technologies' => ['Symfony 7', 'Twig', 'Doctrine', 'MariaDB'],
                'projectType' => 'Site associatif',
                'projectCategory' => 'client',
                'position' => 1,
                'isPublished' => true,
            ],
            [
                'title' => 'VaryaCode.fr',
                'slug' => 'varyacode',
                'shortDescription' => 'Site vitrine professionnel avec formulaire de devis intelligent.',
                'description' => 'Mon propre site, conçu comme une vitrine et un outil de prospection. Formulaire de devis intelligent avec suggestion automatique d\'offre, back-office pour gérer le portfolio et les demandes, le tout sous Symfony.',
                'technologies' => ['Symfony 7', 'Twig', 'Tailwind CSS', 'Doctrine', 'MariaDB', 'Docker'],
                'projectType' => 'Site Vitrine',
                'projectCategory' => 'personal',
                'position' => 2,
                'isPublished' => true,
            ],
            [
                'title' => 'Modèle site indépendant',
                'slug' => 'modele-site-independant',
                'shortDescription' => 'Template réutilisable pour sites vitrines, déclinable rapidement.',
                'description' => 'Un modèle de site vitrine prêt à l\'emploi, conçu pour être décliné rapidement selon le profil du client. Structure modulaire, design adaptable, pensé pour réduire le temps de livraison sans sacrifier la qualité.',
                'technologies' => ['Symfony', 'Twig', 'Doctrine', 'MariaDB'],
                'projectType' => 'Template',
                'projectCategory' => 'personal',
                'position' => 3,
                'isPublished' => true,
            ],
            [
                'title' => 'Landing page — Thérapeute bien-être',
                'slug' => 'landing-therapeute',
                'shortDescription' => 'Exemple concret de l\'offre Landing Page Express à 150 €.',
                'description' => 'Exemple concret de ce à quoi ressemble une landing page livrée dans le cadre de l\'offre à 150 €. Une page unique, sobre et responsive, pensée pour un praticien bien-être qui veut être visible en ligne rapidement.',
                'technologies' => ['HTML', 'CSS'],
                'projectType' => 'Landing Page',
                'projectCategory' => 'demo',
                'position' => 4,
                'isPublished' => true,
            ],
            [
                'title' => 'Planning semaine + Todo list',
                'slug' => 'planning-todo',
                'shortDescription' => 'Outil de productivité avec interface dynamique.',
                'description' => 'Petit outil de productivité pour organiser sa semaine et gérer ses tâches. Interface dynamique, gestion d\'état côté client. Un projet qui montre mes compétences front en complément de mon expertise backend.',
                'technologies' => ['React', 'JavaScript'],
                'projectType' => 'Application web',
                'projectCategory' => 'personal',
                'position' => 5,
                'isPublished' => true,
            ],
        ];

        foreach ($projects as $data) {
            $project = new Project();
            $project->setTitle($data['title']);
            $project->setSlug($data['slug']);
            $project->setShortDescription($data['shortDescription']);
            $project->setDescription($data['description']);
            $project->setTechnologies($data['technologies']);
            $project->setProjectType($data['projectType']);
            $project->setProjectCategory($data['projectCategory']);
            $project->setPosition($data['position']);
            $project->setIsPublished($data['isPublished']);
            $manager->persist($project);
        }

        $manager->flush();
    }
}
