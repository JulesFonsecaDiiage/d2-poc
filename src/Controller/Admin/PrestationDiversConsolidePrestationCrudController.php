<?php

namespace App\Controller\Admin;

use App\Entity\PrestationDiversConsolidePrestation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PrestationDiversConsolidePrestationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PrestationDiversConsolidePrestation::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
