<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Expr\Variable;
use PHPStan\Analyser\Scope;
use Rector\Core\NodeAnalyzer\CompactFuncCallAnalyzer;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Printer\BetterStandardPrinter;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class ExprUsedInNodeAnalyzer
{
    public function __construct(
        private NodeComparator $nodeComparator,
        private UsedVariableNameAnalyzer $usedVariableNameAnalyzer,
        private CompactFuncCallAnalyzer $compactFuncCallAnalyzer,
        private BetterStandardPrinter $betterStandardPrinter
    ) {
    }

    public function isUsed(Node $node, Expr $expr): bool
    {
        if ($node instanceof Include_) {
            return true;
        }

        // variable as variable variable need mark as used
        if ($node instanceof Variable && $expr instanceof Variable) {
            $print = $this->betterStandardPrinter->print($node);
            if (\str_starts_with($print, '${$')) {
                return true;
            }
        }

        if ($node instanceof FuncCall && $expr instanceof Variable) {
            if ($this->compactFuncCallAnalyzer->isInCompact($node, $expr)) {
                return true;
            }

            // handle renamed function call and add arg @see https://github.com/rectorphp/rector/issues/6675
            $scope = $node->getAttribute(AttributeKey::SCOPE);
            return ! $scope instanceof Scope;
        }

        if ($expr instanceof Variable) {
            return $this->usedVariableNameAnalyzer->isVariableNamed($node, $expr);
        }

        return $this->nodeComparator->areNodesEqual($node, $expr);
    }
}
