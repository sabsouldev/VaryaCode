<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Projet')
            ->setEntityLabelInPlural('Portfolio')
            ->setDefaultSort(['position' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
    yield IdField::new('id')->hideOnForm();
    yield TextField::new('title', 'Titre');
    yield SlugField::new('slug')->setTargetFieldName('title')->hideOnIndex();
    yield TextField::new('shortDescription', 'Description courte');
    yield TextareaField::new('description', 'Description complète')->hideOnIndex();
    yield ImageField::new('imageFilename', 'Image')
        ->setBasePath('uploads/projects')
        ->setUploadDir('public/uploads/projects')
        ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
        ->setRequired(false);
     
    yield TextField::new('technologiesAsString', 'Technologies')
        ->setHelp('Séparées par des virgules (ex: Symfony 7, Twig, MariaDB)');
    

    yield TextField::new('projectType', 'Type de projet');
    yield ChoiceField::new('projectCategory', 'Catégorie')
        ->setChoices([
            'Projet client' => 'client',
            'Projet personnel' => 'personal',
            'Démonstration' => 'demo',
        ]);
    yield UrlField::new('externalUrl', 'URL externe')->hideOnIndex();
    yield BooleanField::new('isPublished', 'Publié');
    yield IntegerField::new('position', 'Position');
    yield DateTimeField::new('createdAt', 'Créé le')->hideOnForm();
    }
}
