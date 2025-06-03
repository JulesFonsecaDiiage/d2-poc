<?php

namespace App\Controller\Admin;

use App\Entity\Default\Entite;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EntiteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Entite::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Entité')
            ->setEntityLabelInPlural('Entités')
            ->showEntityActionsInlined()
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Dénomination'),
            EmailField::new('email', 'Email'),
            BooleanField::new('active', 'Actif'),
            DateTimeField::new('created_at', 'Date Creation')->onlyOnDetail()
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('name')
            ->add('email')
            ->add('active');
    }

    public function createEntity(string $entityFqcn): Entite
    {
        // On génère un UUID pour l'entité
        $entite = new Entite();
        $entite->setUuid(bin2hex(random_bytes(16)));
        $entite->setCreatedAt(new \DateTimeImmutable());

        return $entite;
    }
}
