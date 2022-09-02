<?php

declare(strict_types=1);

namespace Rector\CodeQuality\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;

final class VariableNameUsedNextAnalyzer
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly NodeNameResolver $nodeNameResolver
    ) {
    }

    public function isValueVarUsedNext(For_|Foreach_ $for, string $valueVarName): bool
    {
        return (bool) $this->betterNodeFinder->findFirstNext($for, function (Node $node) use ($valueVarName): bool {
            if (! $node instanceof Variable) {
                return false;
            }

            return $this->nodeNameResolver->isName($node, $valueVarName);
        });
    }
}
