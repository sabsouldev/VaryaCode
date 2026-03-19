<?php

namespace App\Controller\Admin;

use App\Entity\DevisRequest;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class DevisRequestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DevisRequest::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Demande de devis')
            ->setEntityLabelInPlural('Demandes de devis')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('status')->setChoices([
                'Nouveau' => 'new',
                'Contacté' => 'contacted',
                'Devis envoyé' => 'quoted',
                'Gagné' => 'won',
                'Perdu' => 'lost',
            ]));
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('contactName', 'Nom');
        yield EmailField::new('contactEmail', 'Email');
        yield TelephoneField::new('contactPhone', 'Téléphone')->hideOnIndex();
        yield TextField::new('structureType', 'Type de structure')->hideOnIndex();
        yield BooleanField::new('hasExistingSite', 'Site existant')->hideOnIndex()->renderAsSwitch(false);
        yield TextField::new('mainObjective', 'Objectif')->hideOnIndex();
        yield TextField::new('estimatedPages', 'Pages')->hideOnIndex();
        yield TextField::new('needsAutonomy', 'Autonomie')->hideOnIndex();
        yield ArrayField::new('features', 'Fonctionnalités')->hideOnIndex();
        yield TextField::new('budget', 'Budget');
        yield TextField::new('timeline', 'Délai')->hideOnIndex();
        yield TextareaField::new('additionalMessage', 'Message')->hideOnIndex();
        yield TextField::new('suggestedOffer', 'Offre suggérée');
        yield ChoiceField::new('status', 'Statut')
            ->setChoices([
                'Nouveau' => 'new',
                'Contacté' => 'contacted',
                'Devis envoyé' => 'quoted',
                'Gagné' => 'won',
                'Perdu' => 'lost',
            ])
            ->renderAsBadges([
                'new' => 'warning',
                'contacted' => 'info',
                'quoted' => 'primary',
                'won' => 'success',
                'lost' => 'danger',
            ]);
        yield DateTimeField::new('createdAt', 'Date')->hideOnForm();
    }
}
