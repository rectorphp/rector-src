<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\NodeVisitorAbstract;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;

final class StmtKeyNodeVisitor extends NodeVisitorAbstract implements ScopeResolverNodeVisitorInterface
{
    /**
     * @param Stmt[] $nodes
     */
    public function beforeTraverse(array $nodes)
    {
        $currentNode = current($nodes);

        if (! $currentNode instanceof Stmt) {
            return $nodes;
        }

        $statementDepth = $currentNode->getAttribute(AttributeKey::STATEMENT_DEPTH);
        if ($statementDepth > 0) {
            return $nodes;
        }

        // on target node or no other root stmt, eg: only namespace without declare, no need reindex
        if (count($nodes) === 1) {
            return $nodes;
        }

        foreach ($nodes as $key => $node) {
            $node->setAttribute(AttributeKey::STMT_KEY, $key);
        }
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof StmtsAwareInterface && ! $node instanceof ClassLike && ! $node instanceof Declare_) {
            return null;
        }

        if ($node->stmts === null) {
            return null;
        }

        // re-index stmt key under current node
        foreach ($node->stmts as $key => $childStmt) {
            $childStmt->setAttribute(AttributeKey::STMT_KEY, $key);
        }

        return null;
    }
}
