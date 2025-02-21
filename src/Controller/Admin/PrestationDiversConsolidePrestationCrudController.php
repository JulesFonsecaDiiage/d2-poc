<?php

namespace App\Controller\Admin;

use App\Entity\Facturation\PrestationDiversConsolidePrestation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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
