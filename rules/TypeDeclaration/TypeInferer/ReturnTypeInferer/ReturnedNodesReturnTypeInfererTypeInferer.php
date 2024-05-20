<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeTraverser;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use PHPStan\Type\VoidType;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\PhpParser\AstResolver;
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
        private NodeTypeResolver $nodeTypeResolver,
        private SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private TypeFactory $typeFactory,
        private SplArrayFixedTypeNarrower $splArrayFixedTypeNarrower,
        private ReflectionResolver $reflectionResolver,
        private AstResolver $astResolver
    ) {
    }

    public function inferFunctionLike(FunctionLike $functionLike): Type
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($functionLike);
        if (! $classReflection instanceof ClassReflection) {
            return new MixedType();
        }

        if ($functionLike instanceof ClassMethod && $classReflection->isInterface()) {
            return new MixedType();
        }

        $types = [];

        $localReturnNodes = $this->collectReturns($functionLike);
        if ($localReturnNodes === []) {
            return $this->resolveNoLocalReturnNodes($classReflection, $functionLike);
        }

        foreach ($localReturnNodes as $localReturnNode) {
            $returnedExprType = $localReturnNode->expr instanceof Expr
                ? $this->nodeTypeResolver->getNativeType($localReturnNode->expr)
                : new VoidType();

            $types = $this->appendType($types, $returnedExprType, $localReturnNode);
        }

        if ($this->silentVoidResolver->hasSilentVoid($functionLike)) {
            $types[] = new VoidType();
        }

        return $this->typeFactory->createMixedPassedOrUnionTypeAndKeepConstant($types);
    }

    /**
     * @param Type[] $types
     * @return Type[]
     */
    private function appendType(array $types, Type $returnedExprType, Return_ $return): array
    {
        if ($returnedExprType instanceof MixedType && ! $returnedExprType->isExplicitMixed()
        &&
            (
                $return->expr instanceof FuncCall
                ||
                $return->expr instanceof StaticCall
                ||
                $return->expr instanceof MethodCall
            )

        ) {
            $scope = $return->getAttribute(AttributeKey::SCOPE);
            if (! $scope instanceof Scope) {
                $types[] = $this->splArrayFixedTypeNarrower->narrow($returnedExprType);
                return $types;
            }

            $targetCallike = $this->astResolver->resolveClassMethodOrFunctionFromCall($return->expr, $scope);

            if (! $targetCallike instanceof Node) {
                $types[] = $this->splArrayFixedTypeNarrower->narrow($returnedExprType);
                return $types;
            }

            $returnedExprType = $this->inferFunctionLike($targetCallike);
        }

        $types[] = $this->splArrayFixedTypeNarrower->narrow($returnedExprType);
        return $types;
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
                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
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
