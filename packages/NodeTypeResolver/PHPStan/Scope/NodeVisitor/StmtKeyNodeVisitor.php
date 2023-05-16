<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;

final class StmtKeyNodeVisitor extends NodeVisitorAbstract implements ScopeResolverNodeVisitorInterface
{
    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof StmtsAwareInterface) {
            return null;
        }

        // re-index stmt key under current node
        if ($node->getAttribute(AttributeKey::STMT_KEY) !== null) {
            $this->setStmtKeyAttribute($node);
            return null;
        }

        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parentNode instanceof StmtsAwareInterface) {
            return null;
        }

        // re-index stmt key under parent node
        $this->setStmtKeyAttribute($parentNode);
        return null;
    }

    private function setStmtKeyAttribute(StmtsAwareInterface $stmtsAware): void
    {
        if ($stmtsAware->stmts === null) {
            return;
        }

        foreach ($stmtsAware->stmts as $key => $stmt) {
            $stmt->setAttribute(AttributeKey::STMT_KEY, $key);
        }
    }
}
