<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\NodeTraverser;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedGenericObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\TypeDeclaration\Contract\TypeInferer\ReturnTypeInfererInterface;
use Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser;

final class YieldNodesReturnTypeInfererTypeInferer implements ReturnTypeInfererInterface
{
    public function __construct(
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly TypeFactory $typeFactory,
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly NodeNameResolver $nodeNameResolver
    ) {
    }

    public function inferFunctionLike(FunctionLike $functionLike): Type
    {
        $yieldNodes = $this->findCurrentScopeYieldNodes($functionLike);

        if ($yieldNodes === []) {
            return new MixedType();
        }

        $types = [];
        foreach ($yieldNodes as $yieldNode) {
            $value = $this->resolveYieldValue($yieldNode);
            if (! $value instanceof Expr) {
                continue;
            }

            $resolvedType = $this->nodeTypeResolver->getType($value);
            if ($resolvedType instanceof MixedType) {
                continue;
            }

            $types[] = $resolvedType;
        }

        $returnType = $functionLike->getReturnType();
        $className = 'Generator';

        if ($returnType instanceof Identifier && $returnType->name === 'iterable') {
            $className = 'Iterator';
        }

        if ($returnType instanceof Name && ! $this->nodeNameResolver->isName($returnType, 'Generator')) {
            $className = $this->nodeNameResolver->getName($returnType);
        }

        if ($types === []) {
            return new FullyQualifiedObjectType($className);
        }

        $types = $this->typeFactory->createMixedPassedOrUnionType($types);
        return new FullyQualifiedGenericObjectType($className, [$types]);
    }

    public function getPriority(): int
    {
        return 1200;
    }

    /**
     * @return Yield_[]|YieldFrom[]
     */
    private function findCurrentScopeYieldNodes(FunctionLike $functionLike): array
    {
        $yieldNodes = [];

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable((array) $functionLike->getStmts(), function (
            Node $node
        ) use (&$yieldNodes): ?int {
            // skip nested scope
            if ($node instanceof FunctionLike) {
                return NodeTraverser::DONT_TRAVERSE_CHILDREN;
            }

            if (! $node instanceof Yield_ && ! $node instanceof YieldFrom) {
                return null;
            }

            $yieldNodes[] = $node;
            return null;
        });

        return $yieldNodes;
    }

    private function resolveYieldValue(Yield_ | YieldFrom $yieldExpr): ?Expr
    {
        if ($yieldExpr instanceof Yield_) {
            return $yieldExpr->value;
        }

        return $yieldExpr->expr;
    }
}
