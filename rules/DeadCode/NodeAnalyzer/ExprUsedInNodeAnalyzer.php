<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Expr\Variable;
use Rector\NodeAnalyzer\CompactFuncCallAnalyzer;

final readonly class ExprUsedInNodeAnalyzer
{
    public function __construct(
        private UsedVariableNameAnalyzer $usedVariableNameAnalyzer,
        private CompactFuncCallAnalyzer $compactFuncCallAnalyzer
    ) {
    }

    public function isUsed(Node $node, Variable $variable): bool
    {
        if ($node instanceof Include_) {
            return true;
        }

        // variable as variable variable need mark as used
        if ($node instanceof Variable && $node->name instanceof Expr) {
            return true;
        }

        if ($node instanceof FuncCall) {
            return $this->compactFuncCallAnalyzer->isInCompact($node, $variable);
        }

        return $this->usedVariableNameAnalyzer->isVariableNamed($node, $variable);
    }
}
