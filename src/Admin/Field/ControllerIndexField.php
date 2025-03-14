<?php

namespace App\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\ComparisonType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Field that shows list of different controller as a simple field
 */
final class ControllerIndexField implements FieldInterface
{
    use FieldTrait;

    public const OPT_REQUEST = 'request';
    public const OPT_PAGE_SIZE = 'page_size';

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('admin/field/html_field.html.twig')
            ->onlyOnDetail()
            ->setRequest(new Request());
    }

    public function setControllerFqcn(string $controllerFqcn): self
    {
        $this->getRequest()
            ->query
            ->set(EA::CRUD_CONTROLLER_FQCN, $controllerFqcn);

        return $this;
    }

    public function setFilter(
        string $field,
        string $value,
        string $comparisonType = ComparisonType::EQ
    ): self {
        $currentFilters = $this->getRequest()->query->get(EA::FILTERS);

        $currentFilters[$field] = [
            'comparison' => $comparisonType,
            'value' => $value
        ];

        $this->getRequest()->query->set(EA::FILTERS, $currentFilters);

        return $this;
    }
    
    public function setSort(
        string $field,
        string $direction,
        ?bool $resetSort = true
    ): self {
        if ($resetSort) {
            $currentSort = [];
        } else {
            $currentSort = $this->getRequest()->query->get(EA::SORT);
        }

        $currentSort[$field] = $direction;

        $this->getRequest()->query->set(EA::SORT, $currentSort);

        return $this;
    }

    public function setFilters(array $filters): self
    {
        $this->getRequest()->query->set(EA::FILTERS, $filters);

        return $this;
    }

    public function setRequest(Request $request): self
    {
        $this->setCustomOption(self::OPT_REQUEST, $request);

        return $this;
    }

    public function getRequest(): Request
    {
        return $this->getAsDto()->getCustomOption(self::OPT_REQUEST);
    }

    public function setPageSize(int $pageSize): self
    {
        $this->setCustomOption(self::OPT_PAGE_SIZE, $pageSize);

        return $this;
    }
}
