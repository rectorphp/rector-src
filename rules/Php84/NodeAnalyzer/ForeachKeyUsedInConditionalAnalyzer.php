<?php

declare(strict_types=1);

namespace Rector\Php84\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpParser\Node\BetterNodeFinder;

final readonly class ForeachKeyUsedInConditionalAnalyzer
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    public function isUsed(Variable $variable, Expr $expr): bool
    {
        $keyVarName = (string) $this->nodeNameResolver->getName($variable);
        return (bool) $this->betterNodeFinder->findVariableOfName($expr, $keyVarName);
    }
}
