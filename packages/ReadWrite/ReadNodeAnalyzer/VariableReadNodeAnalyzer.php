<?php

declare(strict_types=1);

namespace Rector\ReadWrite\ReadNodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use Rector\NodeNestingScope\ParentScopeFinder;
use Rector\ReadWrite\Contract\ReadNodeAnalyzerInterface;
use Rector\ReadWrite\NodeFinder\NodeUsageFinder;

/**
 * @implements ReadNodeAnalyzerInterface<Variable>
 */
final class VariableReadNodeAnalyzer implements ReadNodeAnalyzerInterface
{
    public function __construct(
        private readonly ParentScopeFinder $parentScopeFinder,
        private readonly NodeUsageFinder $nodeUsageFinder,
        private readonly JustReadExprAnalyzer $justReadExprAnalyzer
    ) {
    }

    public function supports(Expr $expr): bool
    {
        return $expr instanceof Variable;
    }

    /**
     * @param Variable $expr
     */
    public function isRead(Expr $expr): bool
    {
        if ($this->justReadExprAnalyzer->isReadContext($expr)) {
            return true;
        }

        return false;
    }
}
