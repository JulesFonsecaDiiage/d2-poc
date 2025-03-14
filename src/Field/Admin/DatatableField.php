<?php

namespace App\Field\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

class DatatableField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('admin/fields/datatable.html.twig');
    }

    /**
     * Set the columns to display in the table
     * @param array $columns
     * @return $this
     */
    public function setDataColumns(array $columns): self
    {
        $this->setCustomOption('columns', $columns);
        return $this;
    }

    /**
     * Set the actions to display in the table
     * @param array $actions
     * @return $this
     */
    public function setActions(array $actions): self
    {
        $this->setCustomOption('actions', $actions);
        return $this;
    }
}