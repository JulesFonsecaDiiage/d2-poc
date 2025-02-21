<?php

namespace App\Controller\Admin;

use App\DTO\EntiteDto;
use App\Service\EntiteService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EntiteVirtualCrudController extends AbstractCrudController
{
    private EntiteService $entiteService;
    private EntityManagerInterface $entityManager;

    public function __construct(EntiteService $entiteService, EntityManagerInterface $entityManager)
    {
        $this->entiteService = $entiteService;
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return EntiteDto::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Entité')
            ->setEntityLabelInPlural('Entités')
            ->setSearchFields(['name', 'email'])
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Dénomination'),
            EmailField::new('email', 'Email'),
            BooleanField::new('active', 'Actif'),
            NumberField::new('montantTotalPrestationDiversConsolide', 'Montant total des prestations divers consolidées'),
            NumberField::new('nombrePrestationDiversConsolidePrestation', 'Nombre de prestations divers consolidées'),
        ];
    }

//    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters)
//    {
//        // On récupère les données depuis le service
//        $data = $this->entiteService->findBy([]);
//
//        // On transforme les tableaux en objets EntiteDto
//        $dtos = new ArrayCollection();
//
//        foreach ($data as $item) {
//            $dtos->add(new EntiteDto(
//                $item['id'],
//                $item['uuid'],
//                $item['prestationDiversConsolides'],
//                $item['active'],
//                $item['name'],
//                $item['email'],
//                $item['montantTotalPrestationDiversConsolide'],
//                $item['nombrePrestationDiversConsolidePrestation']
//            ));
//        }
//
//        return $dtos;
//    }
}
