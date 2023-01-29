<?php

declare(strict_types=1);

namespace Rector\VendorLocker\NodeVendorLocker;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\VoidType;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\FamilyTree\Reflection\FamilyRelationsAnalyzer;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;
use Rector\VendorLocker\ParentClassMethodTypeOverrideGuard;

final class ClassMethodReturnTypeOverrideGuard
{
    /**
     * @var array<class-string, array<string>>
     */
    private const CHAOTIC_CLASS_METHOD_NAMES = [
        'PhpParser\NodeVisitor' => ['enterNode', 'leaveNode', 'beforeTraverse', 'afterTraverse'],
    ];

    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly FamilyRelationsAnalyzer $familyRelationsAnalyzer,
        private readonly AstResolver $astResolver,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly ReturnTypeInferer $returnTypeInferer,
        private readonly ParentClassMethodTypeOverrideGuard $parentClassMethodTypeOverrideGuard,
        private readonly NodeComparator $nodeComparator
    ) {
    }

    public function shouldSkipClassMethod(ClassMethod $classMethod, Node $node): bool
    {
        // 1. skip magic methods
        if ($classMethod->isMagic()) {
            return true;
        }

        // 2. skip chaotic contract class methods
        if ($this->shouldSkipChaoticClassMethods($classMethod)) {
            return true;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($classMethod);
        if (! $classReflection instanceof ClassReflection) {
            return true;
        }

        if (! $this->parentClassMethodTypeOverrideGuard->isReturnTypeChangeAllowed($classMethod)) {
            return true;
        }

        $childrenClassReflections = $this->familyRelationsAnalyzer->getChildrenOfClassReflection($classReflection);
        if ($childrenClassReflections === []) {
            return false;
        }

        if ($classMethod->returnType instanceof Node) {
            return true;
        }

        return $this->shouldSkipHasChildHasReturnType($childrenClassReflections, $classMethod, $node);
    }

    /**
     * @param ClassReflection[] $childrenClassReflections
     */
    private function shouldSkipHasChildHasReturnType(
        array $childrenClassReflections,
        ClassMethod $classMethod,
        Node $node
    ): bool {
        $returnType = $this->returnTypeInferer->inferFunctionLike($classMethod);

        $methodName = $this->nodeNameResolver->getName($classMethod);
        foreach ($childrenClassReflections as $childClassReflection) {
            if (! $childClassReflection->hasNativeMethod($methodName)) {
                continue;
            }

            $methodReflection = $childClassReflection->getNativeMethod($methodName);
            $method = $this->astResolver->resolveClassMethodFromMethodReflection($methodReflection);

            if (! $method instanceof ClassMethod) {
                continue;
            }

            if ($this->shouldSkipChildTyped($method, $node)) {
                return true;
            }

            $childReturnType = $this->returnTypeInferer->inferFunctionLike($method);
            if ($returnType instanceof VoidType && ! $childReturnType instanceof VoidType) {
                return true;
            }
        }

        return false;
    }

    private function shouldSkipChildTyped(ClassMethod $classMethod, Node $node): bool
    {
        return $classMethod->returnType instanceof Node && ! $this->nodeComparator->areNodesEqual(
            $classMethod->returnType,
            $node
        );
    }

    private function shouldSkipChaoticClassMethods(ClassMethod $classMethod): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($classMethod);
        if (! $classReflection instanceof ClassReflection) {
            return true;
        }

        foreach (self::CHAOTIC_CLASS_METHOD_NAMES as $chaoticClass => $chaoticMethodNames) {
            if (! $this->reflectionProvider->hasClass($chaoticClass)) {
                continue;
            }

            $chaoticClassReflection = $this->reflectionProvider->getClass($chaoticClass);
            if (! $classReflection->isSubclassOf($chaoticClassReflection->getName())) {
                continue;
            }

            return $this->nodeNameResolver->isNames($classMethod, $chaoticMethodNames);
        }

        return false;
    }
}
