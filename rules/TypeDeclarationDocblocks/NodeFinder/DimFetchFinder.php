<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks\NodeFinder;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Variable;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpParser\Node\BetterNodeFinder;

final readonly class DimFetchFinder
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private NodeNameResolver $nodeNameResolver
    ) {
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
