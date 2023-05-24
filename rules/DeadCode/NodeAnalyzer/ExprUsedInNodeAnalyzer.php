<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Expr\Variable;
use Rector\Core\Contract\PhpParser\NodePrinterInterface;
use Rector\Core\NodeAnalyzer\CompactFuncCallAnalyzer;
use Rector\Core\PhpParser\Comparing\NodeComparator;

final class ExprUsedInNodeAnalyzer
{
    public function __construct(
        private readonly NodeComparator $nodeComparator,
        private readonly UsedVariableNameAnalyzer $usedVariableNameAnalyzer,
        private readonly CompactFuncCallAnalyzer $compactFuncCallAnalyzer,
        private readonly NodePrinterInterface $nodePrinter
    ) {
    }

    public function isUsed(Node $node, Variable $variable): bool
    {
        if ($node instanceof Include_) {
            return true;
        }

        // variable as variable variable need mark as used
        if ($node instanceof Variable) {
            $print = $this->nodePrinter->print($node);
            if (\str_starts_with($print, '${$')) {
                return true;
            }
        }

        if ($node instanceof FuncCall && $variable instanceof Variable) {
            return $this->compactFuncCallAnalyzer->isInCompact($node, $variable);
        }

        if ($variable instanceof Variable) {
            return $this->usedVariableNameAnalyzer->isVariableNamed($node, $variable);
        }

        return $this->nodeComparator->areNodesEqual($node, $variable);
    }
}
