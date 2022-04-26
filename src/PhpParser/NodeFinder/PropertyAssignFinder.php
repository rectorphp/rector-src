<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\NodeFinder;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Stmt\ClassLike;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Removing\NodeAnalyzer\ForbiddenPropertyRemovalAnalyzer;

final class PropertyAssignFinder
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly NodeComparator $nodeComparator,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ForbiddenPropertyRemovalAnalyzer $forbiddenPropertyRemovalAnalyzer,
    ) {
    }

    public function findAssign(PropertyFetch | StaticPropertyFetch $expr): ?Assign
    {
        $assign = $expr->getAttribute(AttributeKey::PARENT_NODE);

        while ($assign instanceof Node && ! $assign instanceof Assign) {
            $assign = $assign->getAttribute(AttributeKey::PARENT_NODE);
        }

        if (! $assign instanceof Assign) {
            return null;
        }

        $isInExpr = (bool) $this->betterNodeFinder->findFirst(
            $assign->expr,
            fn (Node $subNode): bool => $this->nodeComparator->areNodesEqual($subNode, $expr)
        );

        if ($isInExpr) {
            return null;
        }

        $classLike = $this->betterNodeFinder->findParentType($expr, ClassLike::class);
        $propertyName = (string) $this->nodeNameResolver->getName($expr);

        if ($this->forbiddenPropertyRemovalAnalyzer->isForbiddenInNewCurrentClassNameSelfClone(
            $propertyName,
            $classLike
        )) {
            return null;
        }

        return $assign;
    }
}
