<?php

declare(strict_types=1);

namespace Rector\VendorLocker;

use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariantWithPhpDocs;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\Core\FileSystem\FilePathHelper;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\Core\ValueObject\MethodName;
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
        $parentClassReflections = array_merge($classReflection->getParents(), $classReflection->getInterfaces());

        foreach ($parentClassReflections as $parentClassReflection) {
            if (! $parentClassReflection->hasNativeMethod($methodName)) {
                continue;
            }

            return $parentClassReflection->getNativeMethod($methodName);
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
