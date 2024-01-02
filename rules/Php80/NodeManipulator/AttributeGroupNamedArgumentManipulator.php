<?php

declare(strict_types=1);

namespace Rector\Php80\NodeManipulator;

use PhpParser\Node\AttributeGroup;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Php80\AttributeDecorator\SensioParamConverterAttributeDecorator;

final readonly class AttributeGroupNamedArgumentManipulator
{
    public function __construct(
        private SensioParamConverterAttributeDecorator $sensioParamConverterAttributeDecorator
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

                if ($this->sensioParamConverterAttributeDecorator->getAttributeName() !== $phpAttributeName) {
                    continue;
                }

                $this->sensioParamConverterAttributeDecorator->decorate($attr);
            }
        }
    }
}
