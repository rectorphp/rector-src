<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use Rector\Core\PhpParser\Node\BetterNodeFinder;

final class ExprUsedInNextNodeAnalyzer
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly ExprUsedInNodeAnalyzer $exprUsedInNodeAnalyzer
    ) {
    }

    public function isUsed(Variable $variable): bool
    {
        return (bool) $this->betterNodeFinder->findFirstNext(
            $variable,
            fn (Node $node): bool => $this->exprUsedInNodeAnalyzer->isUsed($node, $variable)
        );
    }
}
