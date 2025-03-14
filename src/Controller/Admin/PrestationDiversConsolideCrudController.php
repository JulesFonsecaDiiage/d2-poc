<?php

namespace App\Controller\Admin;

use App\Admin\Field\ControllerIndexField;
use App\Entity\Default\Entite;
use App\Entity\Facturation\PrestationDiversConsolide;
use App\Filter\Admin\CustomEntityFilter;
use App\Service\PeriodGenerator;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class PrestationDiversConsolideCrudController extends AbstractAdminCrudController
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
        yield $this->newField(TextField::new('uuid_entite', 'Entité')
                ->formatValue(function ($value) {
                    return $this->entityManager->getRepository(Entite::class)->findOneBy(['uuid' => $value])?->getName();
                })
                ->setSortable(false));
        yield $this->newField(DateField::new('periode')->setFormat('Y - MMMM'));
        yield $this->newField(MoneyField::new('total_ht')->setCurrency('EUR'));

        if ($this->isIndexPage($pageName)) {
            yield $this->newField(AssociationField::new('prestationDiversConsolidePrestations', 'Prestations'));
        }

        if ($this->isDetailPage($pageName)) {
            $adminContext = $this->getContext();
            $id = $adminContext->getEntity()->getPrimaryKeyValue();

            yield FormField::addFieldset('Prestations', 'fas fa-list');
            yield ControllerIndexField::new('prestationDiversConsolidePrestations')
                ->setControllerFqcn(PrestationDiversConsolidePrestationCrudController::class)
                ->setFilter('id_consolidation', $id)
                ->setSort('id', 'DESC');
        }
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(CustomEntityFilter::new('uuid_entite')
                ->setClass(Entite::class)
                ->setFilterField('uuid')
                ->canSelectMultiple())
            ->add(ChoiceFilter::new('periode')->setChoices(PeriodGenerator::generatePeriods())->canSelectMultiple())
            ->add('total_ht')
        ;
    }
}
