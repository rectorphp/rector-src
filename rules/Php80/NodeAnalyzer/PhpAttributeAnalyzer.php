<?php

declare(strict_types=1);

namespace Rector\Php80\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpAttribute\Enum\DocTagNodeState;

final class PhpAttributeAnalyzer
{
    public function __construct(
        private readonly AstResolver $astResolver,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    /**
     * @return string[]
     */
    public function getClassNames(Namespace_|FileWithoutNamespace $namespace): array
    {
        $classNames = [];

        $this->betterNodeFinder->find(
            $namespace->stmts,
            function (Node $subNode) use (&$classNames): bool {
                if ($subNode instanceof Attribute) {
                    $classNames[] = $subNode->name->toString();
                    return true;
                }

                return false;
            }
        );

        return $classNames;
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

    public function hasInheritedPhpAttribute(Class_ $class, string $attributeClass): bool
    {
        $className = (string) $this->nodeNameResolver->getName($class);
        if (! $this->reflectionProvider->hasClass($className)) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($className);
        $ancestorClassReflections = array_merge($classReflection->getParents(), $classReflection->getInterfaces());

        foreach ($ancestorClassReflections as $ancestorClassReflection) {
            $ancestorClassName = $ancestorClassReflection->getName();
            $resolvedClass = $this->astResolver->resolveClassFromName($ancestorClassName);

            if (! $resolvedClass instanceof Class_) {
                continue;
            }

            if ($this->hasPhpAttribute($resolvedClass, $attributeClass)) {
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
