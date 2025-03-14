<?php

namespace App\Controller\Admin;

use App\Admin\Filter\AssociationFilter;
use App\Entity\Facturation\PrestationDiversConsolidePrestation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class PrestationDiversConsolidePrestationCrudController extends AbstractAdminCrudController
{
    public static function getEntityFqcn(): string
    {
        return PrestationDiversConsolidePrestation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Prestation Divers ')
            ->setEntityLabelInPlural('Prestations Divers')
            ->setDefaultSort([
                'id' => 'ASC',
            ])
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            $this->newField(IdField::new('id')),
            $this->newField(IntegerField::new('qte', 'Quantité')),
            $this->newField(MoneyField::new('prix_unitaire_ht', 'Montant unitaire (HT)')
                ->setCurrency('EUR')),
            $this->newField(MoneyField::new('prix_total_ht', 'Montant total (HT)')
                ->setCurrency('EUR')),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('id')
            ->add(AssociationFilter::new('id_consolidation', 'Prestation divers consolide', 'prestation_divers_consolide'))
        ;
    }
}
