<?php

declare(strict_types=1);

namespace Rector\Php80\NodeManipulator;

use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;

final class AttributeGroupNamedArgumentManipulator
{
    /**
     * @param AttributeGroup[] $attributeGroups
     * @return AttributeGroup[]
     */
    public function processSpecialClassTypes(array $attributeGroups): array
    {
        foreach ($attributeGroups as $attributeGroup) {
            $attrs = $attributeGroup->attrs;

            foreach ($attrs as $attr) {
                $attrName = ltrim($attr->name->toString(), '\\');
                $this->processReplaceAttr($attr, $attrName);
            }
        }

        return $attributeGroups;
    }

    /**
     * Special case for JMS Access type, where string is replaced by specific value
     */
    private function processReplaceAttr(Attribute $attribute, string $attrName): void
    {
        // @todo add an interface, with array and collector here

        $fqnAttributeName = $attribute->name->getAttribute('attribute_name');
        if ($fqnAttributeName === 'Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter') {
            // make first named arg silent, @see https://github.com/rectorphp/rector/issues/7352
            $firstArg = $attribute->args[0];
            $firstArg->name = null;
        }

        if ($fqnAttributeName === 'JMS\Serializer\Annotation\AccessType') {
            $args = $attribute->args;
            if (count($args) !== 1) {
                return;
            }

            $currentArg = $args[0];
            if ($currentArg->name !== null) {
                return;
            }

            if (! $currentArg->value instanceof String_) {
                return;
            }

            $currentArg->name = new Identifier('type');
        }
    }
}
