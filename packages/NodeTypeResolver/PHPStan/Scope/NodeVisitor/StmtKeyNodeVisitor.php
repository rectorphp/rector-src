<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;

final class StmtKeyNodeVisitor extends NodeVisitorAbstract implements ScopeResolverNodeVisitorInterface
{
    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser
    ) {
    }

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

    private function setStmtKeyAttribute(StmtsAwareInterface $node): void
    {
        if ($node->stmts === null) {
            return;
        }

        foreach ($node->stmts as $key => $stmt) {
            $stmt->setAttribute(AttributeKey::STMT_KEY, $key);
        }
    }
}
