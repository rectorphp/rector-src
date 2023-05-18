<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;

final class StmtKeyNodeVisitor extends NodeVisitorAbstract implements ScopeResolverNodeVisitorInterface
{
    public function __construct(
        private readonly CurrentFileProvider $currentFileProvider
    ) {
    }

    /**
     * @param Node[] $nodes
     * @return Node[]
     */
    public function afterTraverse(array $nodes): array
    {
        foreach ($nodes as $key => $node) {
            if ($node instanceof Stmt) {
                $node->setAttribute(AttributeKey::STMT_KEY, $key);
            }
        }

        return $nodes;
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof StmtsAwareInterface) {
            return null;
        }

        // re-index stmt key under current node
        $this->setStmtKeyAttribute($node);
        return null;
    }

    private function setStmtKeyAttribute(StmtsAwareInterface $stmt): void
    {
        if ($stmt->stmts === null) {
            return;
        }

        foreach ($stmt->stmts as $key => $childStmt) {
            $childStmt->setAttribute(AttributeKey::STMT_KEY, $key);
        }
    }
}
