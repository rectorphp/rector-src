<?php

declare(strict_types=1);

namespace Rector\VendorLocker\NodeVendorLocker;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\VoidType;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\FamilyTree\Reflection\FamilyRelationsAnalyzer;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;
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
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly AstResolver $astResolver,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly ReturnTypeInferer $returnTypeInferer,
        private readonly ParentClassMethodTypeOverrideGuard $parentClassMethodTypeOverrideGuard,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly NodeComparator $nodeComparator
    ) {
    }

    public function shouldSkipClassMethod(ClassMethod $classMethod, \PHPStan\Type\Type|Node $type): bool
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

        if ($this->shouldSkipHasChildHasReturnType($childrenClassReflections, $classMethod, $type)) {
            return true;
        }

        return ! $this->hasClassMethodExprReturn($classMethod);
    }

    /**
     * @param ClassReflection[] $childrenClassReflections
     */
    private function shouldSkipHasChildHasReturnType(
        array $childrenClassReflections,
        ClassMethod $classMethod,
        \PHPStan\Type\Type|Node $type
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

            if ($method->returnType instanceof Node) {
                if ($type instanceof \PHPStan\Type\Type && $this->parentClassMethodTypeOverrideGuard->shouldSkipReturnTypeChange(
                    $method,
                    $type
                )) {
                    return true;
                }

                if ($type instanceof Node && $this->nodeComparator->areNodesEqual($method->returnType, $type)) {
                    return true;
                }
            }

            $childReturnType = $this->returnTypeInferer->inferFunctionLike($method);
            if ($returnType instanceof VoidType && ! $childReturnType instanceof VoidType) {
                return true;
            }
        }

        return false;
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

    private function hasClassMethodExprReturn(ClassMethod $classMethod): bool
    {
        return (bool) $this->betterNodeFinder->findFirst(
            (array) $classMethod->stmts,
            static function (Node $node): bool {
                if (! $node instanceof Return_) {
                    return false;
                }

                return $node->expr instanceof Expr;
            }
        );
    }
}
