<?php

declare(strict_types=1);

namespace Rector\VendorLocker;

use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Type;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\Reflection\ClassReflectionAnalyzer;
use Rector\Reflection\ReflectionResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\VendorLocker\Exception\UnresolvableClassException;

final class ParentClassMethodTypeOverrideGuard
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly TypeComparator $typeComparator,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly ClassReflectionAnalyzer $classReflectionAnalyzer
    ) {
    }

    public function hasParentClassMethod(ClassMethod|MethodReflection $classMethod): bool
    {
        try {
            $parentClassMethod = $this->resolveParentClassMethod($classMethod);

            return $parentClassMethod instanceof MethodReflection;
        } catch (UnresolvableClassException) {
            // we don't know all involved parents,
            // marking as parent exists which usually means the method is guarded against overrides.
            return true;
        }
    }

    public function getParentClassMethod(ClassMethod|MethodReflection $classMethod): ?MethodReflection
    {
        try {
            return $this->resolveParentClassMethod($classMethod);
        } catch (UnresolvableClassException) {
            return null;
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

    private function resolveParentClassMethod(ClassMethod|MethodReflection $classMethod): ?MethodReflection
    {
        if ($classMethod instanceof ClassMethod) {
            $classReflection = $this->reflectionResolver->resolveClassReflection($classMethod);
            if (! $classReflection instanceof ClassReflection) {
                // we can't resolve the class, so we don't know.
                throw new UnresolvableClassException();
            }

            /** @var string $methodName */
            $methodName = $this->nodeNameResolver->getName($classMethod);
        } else {
            $classReflection = $classMethod->getDeclaringClass();
            $methodName = $classMethod->getName();
        }

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
        return $this->classReflectionAnalyzer->resolveParentClassName($classReflection) !== null;
    }
}
