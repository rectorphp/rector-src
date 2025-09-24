<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks\NodeFinder;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpParser\Node\BetterNodeFinder;

final readonly class ArrayDimFetchFinder
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    /**
     * @return Expr[]
     */
    public function findDimFetchAssignToVariableName(ClassMethod $classMethod, string $variableName): array
    {
        $assigns = $this->betterNodeFinder->findInstancesOfScoped((array) $classMethod->stmts, Assign::class);

        $exprs = [];
        foreach ($assigns as $assign) {
            if (! $assign->var instanceof ArrayDimFetch) {
                continue;
            }

            $arrayDimFetch = $assign->var;
            if (! $arrayDimFetch->var instanceof Variable) {
                continue;
            }

            if (! $this->nodeNameResolver->isName($arrayDimFetch->var, $variableName)) {
                continue;
            }

            $exprs[] = $assign->expr;
        }

        return $exprs;
    }

    /**
     * @return ArrayDimFetch[]
     */
    public function findByVariableName(Node $node, string $variableName): array
    {
        $dimFetches = $this->betterNodeFinder->findInstancesOfScoped([$node], ArrayDimFetch::class);

        return array_filter($dimFetches, function (ArrayDimFetch $arrayDimFetch) use ($variableName): bool {
            if (! $arrayDimFetch->var instanceof Variable) {
                return false;
            }

            return $this->nodeNameResolver->isName($arrayDimFetch->var, $variableName);
        });
    }
}
