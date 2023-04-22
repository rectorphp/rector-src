<?php

declare(strict_types=1);

namespace Rector\VendorLocker;

use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Type;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\StaticTypeMapper;

final class ParentClassMethodTypeOverrideGuard
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly TypeComparator $typeComparator,
        private readonly StaticTypeMapper $staticTypeMapper,
    ) {
    }

    public function hasParentClassMethod(ClassMethod $classMethod): bool
    {
        return $this->getParentClassMethod($classMethod) instanceof MethodReflection;
    }

    public function getParentClassMethod(ClassMethod $classMethod): ?MethodReflection
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($classMethod);
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        /** @var string $methodName */
        $methodName = $this->nodeNameResolver->getName($classMethod);
        $parentClassReflection = $classReflection->getParentClass();
        while  ($parentClassReflection !== null) {
            if ($parentClassReflection->hasNativeMethod($methodName)) {
                return $parentClassReflection->getNativeMethod($methodName);
            }
            $parentClassReflection = $parentClassReflection->getParentClass();
        }

        foreach ($classReflection->getInterfaces() as $interfaceReflection) {
            if (! $interfaceReflection->hasNativeMethod($methodName)) {
                continue;
            }

            return $interfaceReflection->getNativeMethod($methodName);
        }

        return null;
    }

    public function shouldSkipReturnTypeChange(ClassMethod $classMethod, Type $parentType): bool
    {
        if ($classMethod->returnType === null) {
            return false;
        }

        $currentReturnType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($classMethod->returnType);

        if ($this->typeComparator->isSubtype($currentReturnType, $parentType)) {
            return true;
        }

        return $this->typeComparator->areTypesEqual($currentReturnType, $parentType);
    }
}
