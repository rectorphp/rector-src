<?php

declare(strict_types=1);

namespace Rector\Php80\NodeAnalyzer;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\PhpParser\AstResolver;
use Rector\NodeNameResolver\NodeNameResolver;

final class PhpAttributeAnalyzer
{
    public function __construct(
        private readonly AstResolver $astResolver,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ReflectionProvider $reflectionProvider,
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
}
