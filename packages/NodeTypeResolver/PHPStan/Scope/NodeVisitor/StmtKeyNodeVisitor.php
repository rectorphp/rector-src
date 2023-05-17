<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeVisitorAbstract;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;

final class StmtKeyNodeVisitor extends NodeVisitorAbstract implements ScopeResolverNodeVisitorInterface
{
    public function beforeTraverse(array $nodes): array
    {
        foreach ($nodes as $key => $node) {
            if ($node instanceof Namespace_ || $node instanceof FileWithoutNamespace) {
                $node->setAttribute(AttributeKey::STMT_KEY, $key);
            }
        }

        return $nodes;
    }

    public function enterNode(Node $node): ?Node
    {
        // need direct Stmt instance check to got every Stmt
        if (! $node instanceof Stmt || $node instanceof ClassLike) {
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

    private function setStmtKeyAttribute(Stmt|StmtsAwareInterface $stmt): void
    {
        if (! $stmt instanceof StmtsAwareInterface) {
            return;
        }

        if ($stmt->stmts === null) {
            return;
        }

        foreach ($stmt->stmts as $key => $childStmt) {
            $childStmt->setAttribute(AttributeKey::STMT_KEY, $key);
        }
    }
}
