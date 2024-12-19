<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\IndexedStmt\Source;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveIndex1Rector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('',   []);
    }

    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node)
    {
        if ($node->expr instanceof String_ && $node->expr->value === 'with index 1') {
            return NodeVisitor::REMOVE_NODE;
        }

        return null;
    }
}