<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeAnalyzer;

use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\DeadCode\ValueObject\VariableAndPropertyFetchAssign;

final class JustPropertyFetchVariableAssignMatcher
{
    public function __construct(
        private readonly NodeComparator $nodeComparator
    ) {
    }

    public function match(StmtsAwareInterface $stmtsAware): ?VariableAndPropertyFetchAssign
    {
        $stmts = (array) $stmtsAware->stmts;

        $stmtCount = count($stmts);

        // must be exactly 3 stmts
        if ($stmtCount !== 3) {
            return null;
        }

        $firstVariableAndPropertyFetchAssign = $this->matchVariableAndPropertyFetchAssign();
        if (! $firstVariableAndPropertyFetchAssign instanceof VariableAndPropertyFetchAssign) {
            return null;
        }

        $thirdVariableAndPropertyFetchAssign = $this->matchRevertedVariableAndPropertyFetchAssign();
        if (! $thirdVariableAndPropertyFetchAssign instanceof VariableAndPropertyFetchAssign) {
            return null;
        }

        // property fetch are the same
        if (! $this->nodeComparator->areNodesEqual(
            $firstVariableAndPropertyFetchAssign->getPropertyFetch(),
            $thirdVariableAndPropertyFetchAssign->getPropertyFetch()
        )) {
            return null;
        }

        // variables are the same
        if (! $this->nodeComparator->areNodesEqual(
            $firstVariableAndPropertyFetchAssign->getVariable(),
            $thirdVariableAndPropertyFetchAssign->getVariable()
        )) {
            return null;
        }

        return $firstVariableAndPropertyFetchAssign;
    }

    private function matchVariableAndPropertyFetchAssign(): ?VariableAndPropertyFetchAssign
    {
        return null;
    }

    private function matchRevertedVariableAndPropertyFetchAssign(): ?VariableAndPropertyFetchAssign
    {
        return null;
    }
}
