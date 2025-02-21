<?php

namespace App\Controller\Admin;

use App\DTO\EntiteDto;
use App\Entity\Default\Entite;
use App\Entity\Facturation\PrestationDiversConsolide;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<img src="/images/logopb.png" alt="Logo">')
            ->setFaviconPath('images/favicon.ico')
            ->setLocales(['fr', 'en'])
        ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('CRM');
        yield MenuItem::linkToCrud('Entité', 'fas fa-users', Entite::class);
        yield MenuItem::linkToCrud('Entité (custom)', 'fas fa-users', EntiteDto::class);

        yield MenuItem::section('Facturation');
        yield MenuItem::subMenu('Prestations', 'fas fa-money-bill')->setSubItems([
            MenuItem::linkToCrud('Divers', null, PrestationDiversConsolide::class),
        ]);
    }

    public function configureActions(): Actions
    {
        return Actions::new()
            ->addBatchAction(Action::BATCH_DELETE)
            ->add(Crud::PAGE_INDEX, Action::NEW)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, Action::DELETE)

            ->update(Crud::PAGE_INDEX, Action::NEW, fn (Action $action) => $action->setIcon('fa fa-plus')->setLabel(false))
            ->update(Crud::PAGE_INDEX, Action::DETAIL, fn (Action $action) => $action->setIcon('fa fa-eye')->setLabel(false))
            ->update(Crud::PAGE_INDEX, Action::DELETE, fn (Action $action) => $action->setIcon('fas fa-trash')->setLabel(false))

            ->add(Crud::PAGE_DETAIL, Action::DELETE)
            ->add(Crud::PAGE_DETAIL, Action::EDIT)
            ->add(Crud::PAGE_DETAIL, Action::INDEX)

            ->update(Crud::PAGE_DETAIL, Action::DELETE, fn (Action $action) => $action->setIcon('fas fa-trash')->setLabel(false)->setCssClass('btn btn-danger text-white'))
            ->update(Crud::PAGE_DETAIL, Action::EDIT, fn (Action $action) => $action->setIcon('fa fa-edit')->setLabel(false))
            ->update(Crud::PAGE_DETAIL, Action::INDEX, fn (Action $action) => $action->setIcon('fa fa-arrow-left')->setLabel(false))

            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)
            ->add(Crud::PAGE_EDIT, Action::INDEX)

            ->update(Crud::PAGE_EDIT, Action::INDEX, fn (Action $action) => $action->setIcon('fa fa-arrow-left')->setLabel(false))

            ->add(Crud::PAGE_NEW, Action::SAVE_AND_RETURN)
            ->add(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER)
            ->add(Crud::PAGE_NEW, Action::INDEX)

            ->update(Crud::PAGE_NEW, Action::INDEX, fn (Action $action) => $action->setIcon('fa fa-arrow-left')->setLabel(false))
        ;
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->showEntityActionsInlined();
    }
}
