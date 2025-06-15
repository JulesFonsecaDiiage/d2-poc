<?php

namespace App\Controller\Admin;

use App\DTO\EntiteDto;
use App\Entity\Default\Entite;
use App\Entity\Default\Expedition;
use App\Entity\Default\Produit;
use App\Entity\Facturation\PrestationDiversConsolide;
use App\Entity\Facturation\PrestationDiversConsolidePrestation;
use App\Repository\Default\EntiteRepository;
use App\Repository\Default\ExpeditionRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\Criteria;
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
    public function __construct(
        private readonly EntiteRepository $entiteRepository,
        private readonly ExpeditionRepository $expeditionRepository,
    ) {}

    public function index(): Response
    {
        $users = $this->entiteRepository->findBy([], ['created_at' => 'DESC'], 10);
        $nbActiveUsers = $this->entiteRepository->count(['active' => true]);

        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->gte('created_at', new DateTimeImmutable('-1 month')))
            ->orderBy(['created_at' => 'DESC']);

        $expeditionsDernierMois = $this->expeditionRepository->matching($criteria);
        $nbExpeditions = $expeditionsDernierMois->count();

        $revenues = $expeditionsDernierMois->reduce(fn(float $total, Expedition $expedition) => $total + $expedition->getTotal(), 0.0);
        $revenues = number_format($revenues, 0, ',', ' ') . ' €';

        $chartData = [];
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->gte('created_at', new \DateTimeImmutable('-1 year')))
            ->orderBy(['created_at' => 'ASC']);

        $formatter = new \IntlDateFormatter('fr_FR', pattern: 'LLLL yyyy');
        $expeditionsDerniereAnnee = $this->expeditionRepository->matching($criteria);

        foreach ($expeditionsDerniereAnnee as $expedition) {
            $date = $formatter->format($expedition->getCreatedAt());
            $chartData[$date] = ($chartData[$date] ?? 0) + $expedition->getTotal();
        }

        $chartLabels = array_keys($chartData);
        $chartValues = array_values($chartData);

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'nbActiveUsers' => $nbActiveUsers,
            'expeditions' => $expeditionsDernierMois,
            'nbExpeditions' => $nbExpeditions,
            'revenues' => $revenues,
            'chartLabels' => json_encode($chartLabels),
            'chartValues' => json_encode($chartValues),
        ]);
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

        yield MenuItem::section('Ventes');
        yield MenuItem::linkToCrud('Produits', 'fas fa-box', Produit::class);
        yield MenuItem::linkToCrud('Expeditions', 'fas fa-shipping-fast', Expedition::class);

        yield MenuItem::section('CRM');
        yield MenuItem::linkToCrud('Entité', 'fas fa-users', Entite::class);
        yield MenuItem::linkToCrud('Entité (custom)', 'fas fa-users', EntiteDto::class);

        yield MenuItem::section('Facturation');
        yield MenuItem::subMenu('Prestations', 'fas fa-money-bill')->setSubItems([
            MenuItem::linkToCrud('Divers (Consolidations)', null, PrestationDiversConsolide::class),
            MenuItem::linkToCrud('Divers (Prestations)', null, PrestationDiversConsolidePrestation::class),
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
            ->showEntityActionsInlined()
            ->overrideTemplate('crud/detail', 'admin/crud/detail.html.twig')
        ;
    }
}
