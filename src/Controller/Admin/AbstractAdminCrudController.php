<?php

namespace App\Controller\Admin;

use App\Admin\Field\ControllerIndexField;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\PaginatorDto;
use EasyCorp\Bundle\EasyAdminBundle\Factory\AdminContextFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\ControllerFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FilterFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\PaginatorFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use function is_string;

abstract class AbstractAdminCrudController extends AbstractCrudController
{
    public const PAGE_LIST_HTML = 'listHtml';
    public const ACTION_LIST_HTML = 'listHtml';

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            Environment::class => '?'.Environment::class,
            AdminContextFactory::class => '?'.AdminContextFactory::class,
        ]);
    }

    public function isIndexPage(string $page): bool
    {
        return $page === Crud::PAGE_INDEX;
    }

    public function isDetailPage(string $page): bool
    {
        return $page === Crud::PAGE_DETAIL;
    }

    /**
     * Returns the list of records from another controller as formatted html
     *
     * @param AdminContext $context
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function listHtml(AdminContext $context): Response
    {
        dump($context);
        dump($context->getRequest()->query->all());

        // Si le contexte ou le Crud est null, reconstruire le contexte
        if (!$context || !$context->getCrud()) {
            $request = $this->container->get('request_stack')->getCurrentRequest();
            $dashboardController = $this->container->get(ControllerFactory::class)
                ->getDashboardControllerInstance(DashboardController::class, $request);

            $crudController = $this->container->get(ControllerFactory::class)
                ->getCrudControllerInstance(
                    $request->query->get(EA::CRUD_CONTROLLER_FQCN),
                    $request->query->get(EA::CRUD_ACTION),
                    $request
                );

            $context = $this->container->get(AdminContextFactory::class)
                ->create($request, $dashboardController, $crudController);
        }

        dump($context);

        $context->getCrud()->setPageName(self::PAGE_LIST_HTML);

        $this->hackPageSizeForListHtml($context);

        // duplicating almost the same steps that are done in index()
        $fields = FieldCollection::new($this->configureFields(self::PAGE_LIST_HTML));
        $filters = $this->container->get(FilterFactory::class)->create($context->getCrud()->getFiltersConfig(), $fields, $context->getEntity());
        $queryBuilder = $this->createIndexQueryBuilder($context->getSearch(), $context->getEntity(), $fields, $filters);
        $paginator = $this->container->get(PaginatorFactory::class)->create($queryBuilder);

        $entities = $this->container->get(EntityFactory::class)->createCollection($context->getEntity(), $paginator->getResults());
        $this->container->get(EntityFactory::class)->processFieldsForAll($entities, $fields);

        $templateParameters = [
            // new context should be passed to twig here cause easy admin passes only globally context from container as ea variable
            'admin_context' => $context,
            'pageName' => self::PAGE_LIST_HTML,
            'entities' => $entities,
            'paginator' => $paginator,
            'filters' => $filters,
        ];

        $formatted = $this->container->get(Environment::class)
            ->render(
                'admin/crud/inner_index.html.twig',
                $templateParameters
            );

        return new Response($formatted);
    }

    private function hackPageSizeForListHtml(AdminContext $context): void
    {
        $request = $context->getRequest();

        $pageSize = $request->query->get(ControllerIndexField::OPT_PAGE_SIZE, 10);

        if ($pageSize > 50) {
            $pageSize = 50;
        }

        // hack with paginator. Rewrite DTO to change the per page items
        $oldPaginatorDto = $context->getCrud()->getPaginator();
        $context->getCrud()->setPaginator(new PaginatorDto(
            $pageSize,
            $oldPaginatorDto->getRangeSize(),
            $oldPaginatorDto->getRangeEdgeSize(),
            $oldPaginatorDto->fetchJoinCollection(),
            $oldPaginatorDto->useOutputWalkers()
        ));
    }

    protected function showOnlyOn(FieldInterface $field, string $page): FieldInterface
    {
        $field
            ->getAsDto()
            ->setDisplayedOn(KeyValueStore::new([
                $page => $page
            ]));

        return $field;
    }

    /**
     * @param string|FieldInterface $field
     * @param string|null $label
     * @return Field
     */
    protected function newField($field, ?string $label = null): FieldInterface
    {
        if (is_string($field)) {
            $field = Field::new($field, $label);
        }

        $this->addDisplayOn($field, self::PAGE_LIST_HTML);

        return $field;
    }
    
    protected function addDisplayOn(FieldInterface $field, string $page): FieldInterface
    {
        $field
            ->getAsDto()
            ->getDisplayedOn()
            ->set($page, $page);

        return $field;
    }
}
