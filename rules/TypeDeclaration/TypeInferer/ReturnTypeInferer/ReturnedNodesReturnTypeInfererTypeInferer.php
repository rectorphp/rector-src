<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use PHPStan\Type\VoidType;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Reflection\ReflectionResolver;
use Rector\TypeDeclaration\TypeInferer\SilentVoidResolver;
use Rector\TypeDeclaration\TypeInferer\SplArrayFixedTypeNarrower;

/**
 * @internal
 */
final readonly class ReturnedNodesReturnTypeInfererTypeInferer
{
    public function __construct(
        private SilentVoidResolver $silentVoidResolver,
        private BetterNodeFinder $betterNodeFinder,
        private NodeTypeResolver $nodeTypeResolver,
        private SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private TypeFactory $typeFactory,
        private SplArrayFixedTypeNarrower $splArrayFixedTypeNarrower,
        private ReflectionResolver $reflectionResolver,
    ) {
    }

    public function inferFunctionLike(ClassMethod|Function_|Closure|ArrowFunction $functionLike): Type
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($functionLike);
        if (! $classReflection instanceof ClassReflection) {
            return new MixedType();
        }

        if ($functionLike instanceof ClassMethod && $classReflection->isInterface()) {
            return new MixedType();
        }

        $types = [];

        $localReturnNodes = $this->betterNodeFinder->findReturnsScoped($functionLike);
        if ($localReturnNodes === []) {
            return $this->resolveNoLocalReturnNodes($classReflection, $functionLike);
        }

        foreach ($localReturnNodes as $localReturnNode) {
            $returnedExprType = $localReturnNode->expr instanceof Expr
                ? $this->nodeTypeResolver->getNativeType($localReturnNode->expr)
                : new VoidType();

            $types[] = $this->splArrayFixedTypeNarrower->narrow($returnedExprType);
        }

        if ($this->silentVoidResolver->hasSilentVoid($functionLike)) {
            $types[] = new VoidType();
        }

        return $this->typeFactory->createMixedPassedOrUnionTypeAndKeepConstant($types);
    }

    private function resolveNoLocalReturnNodes(
        ClassReflection $classReflection,
        FunctionLike $functionLike
    ): VoidType | MixedType {
        // void type
        if (! $this->isAbstractMethod($classReflection, $functionLike)) {
            return new VoidType();
        }

        return new MixedType();
    }

    private function isAbstractMethod(ClassReflection $classReflection, FunctionLike $functionLike): bool
    {
        if ($functionLike instanceof ClassMethod && $functionLike->isAbstract()) {
            return true;
        }

        if (! $classReflection->isClass()) {
            return false;
        }

        return $classReflection->isAbstract();
    }
}
