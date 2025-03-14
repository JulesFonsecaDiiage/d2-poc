<?php

namespace App\Admin\Filter;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Filter\FilterInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDataDto;
use EasyCorp\Bundle\EasyAdminBundle\Filter\FilterTrait;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\TextFilterType;

/**
 * Simple filter that allows filtering by assosiated fields
 */
class AssociationFilter implements FilterInterface
{
    use FilterTrait;

    private string $field = 'id';
    private ?string $associationProperty = null;

    /**
     * @param string $propertyName name of field that will be used as key for identify filter
     * @param string|null $label displayed label of filter
     * @param string|null $associationProperty property name inside of working entity. Using $propertyName by default
     * @return static
     */
    public static function new(
        string $propertyName,
        string $label = null,
        string $associationProperty = null
    ): self {

        if (!$associationProperty) {
            $associationProperty = $propertyName;
        }

        return (new self())
            ->setFilterFqcn(__CLASS__)
            ->setProperty($propertyName)
            ->setAssociationProperty($associationProperty)
            ->setLabel($label)
            ->setFormType(TextFilterType::class);
    }

    public function apply(
        QueryBuilder $queryBuilder,
        FilterDataDto $filterDataDto,
        ?FieldDto $fieldDto,
        EntityDto $entityDto
    ): void {
        if (!$this->field) {
            return;
        }

        $property = sprintf(
            '%s.%s',
            $filterDataDto->getEntityAlias(),
            $this->associationProperty
        );

        $queryBuilder->innerJoin(
            $property,
            'association',
            Join::WITH,
            sprintf('IDENTITY(%s) = association.id', $property)
        );

        $queryBuilder->andWhere(
            sprintf(
                'association.%s %s :value',
                $this->field,
                $filterDataDto->getComparison()
            )
        )
        ->setParameter('value', $filterDataDto->getValue());
    }

    public function setField(string $fieldName): self
    {
        $this->field = $fieldName;

        return $this;
    }

    public function setAssociationProperty(string $associationProperty): self
    {
        $this->associationProperty = $associationProperty;

        return $this;
    }

    public function getParent(): string
    {
        return TextFilterType::class;
    }
}
