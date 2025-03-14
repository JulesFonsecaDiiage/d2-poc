<?php

namespace App\Admin\Field\Configurator;

use App\Admin\Field\ControllerIndexField;
use App\Controller\Admin\AbstractAdminCrudController;
use App\Controller\Admin\DashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldConfiguratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Factory\AdminContextFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\ControllerFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Configures ControllerIndexField::class field that shows list of different controller as a simple field
 */
class ControllerIndexConfigurator implements FieldConfiguratorInterface
{
    private AdminContextFactory $adminContextFactory;
    private ControllerFactory $controllerFactory;
    private RequestStack $requestStack;

    public function __construct(
        AdminContextFactory $adminContextFactory,
        ControllerFactory $controllerFactory,
        RequestStack $requestStack
    ) {
        $this->adminContextFactory = $adminContextFactory;
        $this->controllerFactory = $controllerFactory;
        $this->requestStack = $requestStack;
    }

    public function supports(FieldDto $field, EntityDto $entityDto): bool
    {
        return $field->getFieldFqcn() === ControllerIndexField::class;
    }

    public function configure(FieldDto $field, EntityDto $entityDto, AdminContext $context): void
    {
        /** @var Request $request */
        $request = $field->getCustomOption(ControllerIndexField::OPT_REQUEST);
        $pageSize = $field->getCustomOption(ControllerIndexField::OPT_PAGE_SIZE) ?? 15;

        if (!$request->query->has(ControllerIndexField::OPT_PAGE_SIZE)) {
            $request->query->set(ControllerIndexField::OPT_PAGE_SIZE, $pageSize);
        }

        $formatted = $this->getControllerHtml($request);

        $field->setLabel(null);
        $field->setFormattedValue($formatted);
    }

    /**
     * All the thing are doing here could be interpreting as hack of EasyAdmin
     * Cause from the box bundle not provides such functionality and it's code
     * is very encapsulated with final classes, arrays with options that are not
     * configurable from outside, services that has a state, services that uses
     * current request and so on.
     *
     * @param Request $request
     * @return string
     */
    private function getControllerHtml(Request $request): string
    {
        $controllerFqcn = $request->query->get(EA::CRUD_CONTROLLER_FQCN);
        $request->query->set(EA::CRUD_ACTION, AbstractAdminCrudController::ACTION_LIST_HTML);

        $request->setSession($this->requestStack->getSession());
        $this->requestStack->push($request);

        $dashboardController = $this->controllerFactory
            ->getDashboardControllerInstance(
                DashboardController::class,
                $request
            );

        /** @var AbstractAdminCrudController $crudController */
        $crudController = $this->controllerFactory->getCrudControllerInstance(
            $controllerFqcn,
            AbstractAdminCrudController::ACTION_LIST_HTML,
            $request
        );

        // all this we need just to create another admin context for sub controller (listHtml page)
        // cause current context is setting up for parent controller (detail page)
        $adminContext = $this->adminContextFactory->create($request, $dashboardController, $crudController);
        $adminContext->getCrud()->setPageName(AbstractAdminCrudController::PAGE_LIST_HTML);
        dump($adminContext);

        $request->attributes->set(EA::CONTEXT_REQUEST_ATTRIBUTE, $adminContext);

        dump($request->query->all());
        $response = $crudController->listHtml($adminContext);

        $this->requestStack->pop();

        return $response->getContent();
    }
}