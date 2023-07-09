<?php

declare(strict_types=1);

namespace Rector\CodeQuality\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Foreach_;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;

final class ForeachAnalyzer
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly VariableNameUsedNextAnalyzer $variableNameUsedNextAnalyzer
    ) {
    }

    public function isValueVarUsed(Foreach_ $foreach, string $singularValueVarName): bool
    {
        $isUsedInStmts = (bool) $this->betterNodeFinder->findFirst($foreach->stmts, function (Node $node) use (
            $singularValueVarName
        ): bool {
            if (! $node instanceof Variable) {
                return false;
            }

            return $this->nodeNameResolver->isName($node, $singularValueVarName);
        });

        if ($isUsedInStmts) {
            return true;
        }

        if ($this->variableNameUsedNextAnalyzer->isValueVarUsedNext($foreach, $singularValueVarName)) {
            return true;
        }

        return $this->variableNameUsedNextAnalyzer->isValueVarUsedNext(
            $foreach,
            (string) $this->nodeNameResolver->getName($foreach->valueVar)
        );
    }
}
