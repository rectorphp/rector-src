<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\IndexedStmt\Source;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use PhpParser\NodeVisitor;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
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
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node)
    {
        if ($node->stmts === null) {
            return null;
        }

        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof If_) {
                foreach ($stmt->stmts as $childStmt) {
                    if ($childStmt->getAttribute(AttributeKey::STMT_KEY) === 1 && $childStmt instanceof Expression && $childStmt->expr instanceof String_ && $childStmt->expr->value === 'with index 2') {
                        $childStmt->expr->value = 'final index';
                        return $node;
                    }
                }
            }
        }

        return null;
    }
}