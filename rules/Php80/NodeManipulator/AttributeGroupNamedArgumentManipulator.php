<?php

declare(strict_types=1);

namespace Rector\Php80\NodeManipulator;

use PhpParser\Node\AttributeGroup;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Php80\Contract\ConverterAttributeDecoratorInterface;

final readonly class AttributeGroupNamedArgumentManipulator
{
    /**
     * @param ConverterAttributeDecoratorInterface[] $converterAttributeDecorators
     */
    public function __construct(
        private array $converterAttributeDecorators
    ) {
    }

    /**
     * @param AttributeGroup[] $attributeGroups
     */
    public function decorate(array $attributeGroups): void
    {
        foreach ($attributeGroups as $attributeGroup) {
            foreach ($attributeGroup->attrs as $attr) {
                $phpAttributeName = $attr->name->getAttribute(AttributeKey::PHP_ATTRIBUTE_NAME);

                foreach ($this->converterAttributeDecorators as $converterAttributeDecorator) {
                    if ($converterAttributeDecorator->getAttributeName() !== $phpAttributeName) {
                        continue;
                    }

                    $converterAttributeDecorator->decorate($attr);
                }
            }
        }
    }
}
