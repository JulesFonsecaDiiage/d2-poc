<?php

namespace App\Controller\Admin;

use App\Entity\Default\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('libelle', 'Libelle'),
            MoneyField::new('prix', 'Prix')->setCurrency('EUR'),
            DateTimeField::new('date_creation', 'Date de Création')->hideOnIndex(),
            BooleanField::new('actif', 'Actif'),
        ];
    }
}
