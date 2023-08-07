<?php

declare(strict_types=1);

namespace Rector\VendorLocker;

use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Type;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\Core\Util\Reflection\PrivatesAccessor;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\VendorLocker\Exception\UnresolvableClassException;

final class ParentClassMethodTypeOverrideGuard
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly TypeComparator $typeComparator,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly PrivatesAccessor $privatesAccessor,
    ) {
    }

    public function hasParentClassMethod(ClassMethod $classMethod): ?bool
    {
        try {
            $parentClassMethod = $this->resolveParentClassMethod($classMethod);

            return $parentClassMethod instanceof MethodReflection;
        } catch (UnresolvableClassException) {
            // we don't know all involved parents.
            return null;
        }
    }

    public function getParentClassMethod(ClassMethod $classMethod): ?MethodReflection
    {
        try {
            return $this->resolveParentClassMethod($classMethod);
        } catch (UnresolvableClassException) {
            // we don't know all involved parents.
            throw new ShouldNotHappenException(
                'Unable to resolve involved class. You are likely missing hasParentClassMethod() before calling getParentClassMethod().'
            );
        }
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

    private function resolveParentClassMethod(ClassMethod $classMethod): ?MethodReflection
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($classMethod);
        if (! $classReflection instanceof ClassReflection) {
            // we can't resolve the class, so we don't know.
            throw new UnresolvableClassException();
        }

        /** @var string $methodName */
        $methodName = $this->nodeNameResolver->getName($classMethod);
        $currentClassReflection = $classReflection;
        while ($this->hasClassParent($currentClassReflection)) {
            $parentClassReflection = $currentClassReflection->getParentClass();
            if (! $parentClassReflection instanceof ClassReflection) {
                // per AST we have a parent class, but our reflection classes are not able to load its class definition/signature
                throw new UnresolvableClassException();
            }

            if ($parentClassReflection->hasNativeMethod($methodName)) {
                return $parentClassReflection->getNativeMethod($methodName);
            }

            $currentClassReflection = $parentClassReflection;
        }

        foreach ($classReflection->getInterfaces() as $interfaceReflection) {
            if (! $interfaceReflection->hasNativeMethod($methodName)) {
                continue;
            }

            return $interfaceReflection->getNativeMethod($methodName);
        }

        return null;
    }

    private function hasClassParent(ClassReflection $classReflection): bool
    {
        // XXX rework this hack, after https://github.com/phpstan/phpstan-src/pull/2563 landed
        $nativeReflection = $classReflection->getNativeReflection();
        $betterReflectionClass = $this->privatesAccessor->getPrivateProperty(
            $nativeReflection,
            'betterReflectionClass'
        );
        $parentClassName = $this->privatesAccessor->getPrivateProperty($betterReflectionClass, 'parentClassName');
        return $parentClassName !== null;
    }
}
