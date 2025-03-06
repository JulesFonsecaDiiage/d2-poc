<?php

namespace App\Controller\Admin;

use App\Entity\Default\Entite;
use App\Entity\Facturation\PrestationDiversConsolide;
use App\Filter\Admin\CustomEntityFilter;
use App\Service\PeriodGenerator;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class PrestationDiversConsolideCrudController extends AbstractCrudController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return PrestationDiversConsolide::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Prestation Divers')
            ->setEntityLabelInPlural('Prestations Divers')
            ->setDefaultSort([
                'periode' => 'DESC',
            ])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('uuid_entite', 'Entité')
                ->formatValue(function ($value) {
                    return $this->entityManager->getRepository(Entite::class)->findOneBy(['uuid' => $value])?->getName();
                })
                ->setSortable(false),
            DateField::new('periode')->setFormat('Y - MMMM'),
            MoneyField::new('total_ht')->setCurrency('EUR'),
            AssociationField::new('prestationDiversConsolidePrestations', 'Prestations')
                ->hideOnForm()
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(CustomEntityFilter::new('uuid_entite')
                ->setFormTypeOption('value_type_options', ['class' => Entite::class])
                ->setFilterField('uuid')
                ->canSelectMultiple())
            ->add(ChoiceFilter::new('periode')->setChoices(PeriodGenerator::generatePeriods())->canSelectMultiple())
            ->add('total_ht')
        ;
    }
}
