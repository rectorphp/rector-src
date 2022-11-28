<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\NodeTraverser;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use PHPStan\Type\VoidType;
use Rector\Core\Contract\PhpParser\NodePrinterInterface;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\TypeDeclaration\TypeInferer\SilentVoidResolver;
use Rector\TypeDeclaration\TypeInferer\SplArrayFixedTypeNarrower;

/**
 * @deprecated
 * @todo Split into many narrow-focused rules
 */
final class ReturnedNodesReturnTypeInfererTypeInferer
{
    public function __construct(
        private readonly SilentVoidResolver $silentVoidResolver,
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly TypeFactory $typeFactory,
        private readonly SplArrayFixedTypeNarrower $splArrayFixedTypeNarrower,
        private readonly AstResolver $reflectionAstResolver,
        private readonly NodePrinterInterface $nodePrinter,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly BetterNodeFinder $betterNodeFinder,
    ) {
    }

    public function inferFunctionLike(FunctionLike $functionLike): Type
    {
        $classLike = $this->betterNodeFinder->findParentType($functionLike, ClassLike::class);
        if (! $classLike instanceof ClassLike) {
            return new MixedType();
        }

        if ($functionLike instanceof ClassMethod && $classLike instanceof Interface_) {
            return new MixedType();
        }

        $types = [];

        $localReturnNodes = $this->collectReturns($functionLike);
        if ($localReturnNodes === []) {
            /** @var Class_|Interface_|Trait_ $classLike */
            return $this->resolveNoLocalReturnNodes($classLike, $functionLike);
        }

        foreach ($localReturnNodes as $localReturnNode) {
            $returnedExprType = $this->nodeTypeResolver->getType($localReturnNode);
            $returnedExprType = $this->correctWithNestedType($returnedExprType, $localReturnNode, $functionLike);

            $types[] = $this->splArrayFixedTypeNarrower->narrow($returnedExprType);
        }

        if ($this->silentVoidResolver->hasSilentVoid($functionLike)) {
            $types[] = new VoidType();
        }

        return $this->typeFactory->createMixedPassedOrUnionType($types);
    }

    /**
     * @return Return_[]
     */
    private function collectReturns(FunctionLike $functionLike): array
    {
        $returns = [];

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable((array) $functionLike->getStmts(), static function (
            Node $node
        ) use (&$returns): ?int {
            // skip Return_ nodes in nested functions or switch statements
            if ($node instanceof FunctionLike) {
                return NodeTraverser::DONT_TRAVERSE_CHILDREN;
            }

            if (! $node instanceof Return_) {
                return null;
            }

            $returns[] = $node;

            return null;
        });

        return $returns;
    }

    private function resolveNoLocalReturnNodes(
        Class_|Interface_|Trait_ $classLike,
        FunctionLike $functionLike
    ): VoidType | MixedType {
        // void type
        if (! $this->isAbstractMethod($classLike, $functionLike)) {
            return new VoidType();
        }

        return new MixedType();
    }

    private function isAbstractMethod(Class_|Interface_|Trait_ $classLike, FunctionLike $functionLike): bool
    {
        if ($functionLike instanceof ClassMethod && $functionLike->isAbstract()) {
            return true;
        }

        if (! $classLike instanceof Class_) {
            return false;
        }

        return $classLike->isAbstract();
    }

    private function inferFromReturnedMethodCall(Return_ $return, FunctionLike $originalFunctionLike): Type
    {
        if (! $return->expr instanceof MethodCall) {
            return new MixedType();
        }

        $methodReflection = $this->reflectionResolver->resolveMethodReflectionFromMethodCall($return->expr);
        if (! $methodReflection instanceof MethodReflection) {
            return new MixedType();
        }

        return $this->resolveClassMethod($methodReflection, $originalFunctionLike);
    }

    private function isArrayTypeMixed(Type $type): bool
    {
        if (! $type instanceof ArrayType) {
            return false;
        }

        if (! $type->getItemType() instanceof MixedType) {
            return false;
        }

        return $type->getKeyType() instanceof MixedType;
    }

    private function correctWithNestedType(Type $resolvedType, Return_ $return, FunctionLike $functionLike): Type
    {
        if ($resolvedType instanceof MixedType || $this->isArrayTypeMixed($resolvedType)) {
            $correctedType = $this->inferFromReturnedMethodCall($return, $functionLike);

            // override only if has some extra value
            if (! $correctedType instanceof MixedType && ! $correctedType instanceof VoidType) {
                return $correctedType;
            }
        }

        return $resolvedType;
    }

    private function resolveClassMethod(MethodReflection $methodReflection, FunctionLike $originalFunctionLike): Type
    {
        $classMethod = $this->reflectionAstResolver->resolveClassMethodFromMethodReflection($methodReflection);
        if (! $classMethod instanceof ClassMethod) {
            return new MixedType();
        }

        $classMethodCacheKey = $this->nodePrinter->print($classMethod);
        $functionLikeCacheKey = $this->nodePrinter->print($originalFunctionLike);

        if ($classMethodCacheKey === $functionLikeCacheKey) {
            return new MixedType();
        }

        return $this->inferFunctionLike($classMethod);
    }
}
