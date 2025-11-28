<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\IndexedStmt\Source;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ChangeLastIndex1Rector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('',   []);
    }

    public function getNodeTypes(): array
    {
        return \Rector\PhpParser\Enum\NodeGroup::STMTS_AWARE;
    }

    /**
     * @param StmtsAware $node
     */
    public function refactor(Node $node)
    {
        if ($node->stmts === null) {
            return null;
        }

        foreach ($node->stmts as $key => $stmt) {
            if ($key === 1 && $stmt instanceof Expression && $stmt->expr instanceof String_ && $stmt->expr->value === 'with index 2') {
                $stmt->expr->value = 'final index';
                return $node;
            }
        }

        return null;
    }
}
