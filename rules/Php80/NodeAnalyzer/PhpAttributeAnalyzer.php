<?php

declare(strict_types=1);

namespace Rector\Php80\NodeAnalyzer;

use PhpParser\Node\Arg;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpAttribute\Enum\DocTagNodeState;

final readonly class PhpAttributeAnalyzer
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
    ) {
    }

    public function hasPhpAttribute(Property | ClassLike | ClassMethod | Param $node, string $attributeClass): bool
    {
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attribute) {
                if (! $this->nodeNameResolver->isName($attribute->name, $attributeClass)) {
                    continue;
                }

                return true;
            }
        }

        return false;
    }

    /**
     * @param string[] $attributeClasses
     */
    public function hasPhpAttributes(Property | ClassLike | ClassMethod | Param $node, array $attributeClasses): bool
    {
        foreach ($attributeClasses as $attributeClass) {
            if ($this->hasPhpAttribute($node, $attributeClass)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param AttributeGroup[] $attributeGroups
     */
    public function hasRemoveArrayState(array $attributeGroups): bool
    {
        foreach ($attributeGroups as $attributeGroup) {
            foreach ($attributeGroup->attrs as $attribute) {
                $args = $attribute->args;

                if ($this->hasArgWithRemoveArrayValue($args)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param Arg[] $args
     */
    private function hasArgWithRemoveArrayValue(array $args): bool
    {
        foreach ($args as $arg) {
            if (! $arg->value instanceof Array_) {
                continue;
            }

            foreach ($arg->value->items as $item) {
                if (! $item instanceof ArrayItem) {
                    continue;
                }

                if ($item->value instanceof String_ && $item->value->value === DocTagNodeState::REMOVE_ARRAY) {
                    return true;
                }
            }
        }

        return false;
    }
}
