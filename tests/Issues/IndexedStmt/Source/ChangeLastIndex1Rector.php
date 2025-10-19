<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\IndexedStmt\Source;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use PhpParser\Node\ContainsStmts;
use Rector\NodeTypeResolver\Node\AttributeKey;
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
        return [ContainsStmts::class];
    }

    /**
     * @param ContainsStmts $node
     */
    public function refactor(Node $node)
    {
        if ($node->stmts === null) {
            return null;
        }

        foreach ($node->stmts as $stmt) {
            if ($stmt->getAttribute(AttributeKey::STMT_KEY) === 1 && $stmt instanceof Expression && $stmt->expr instanceof String_ && $stmt->expr->value === 'with index 2') {
                $stmt->expr->value = 'final index';
                return $node;
            }
        }

        return null;
    }
}
