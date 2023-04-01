<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Nop;
use PhpParser\NodeVisitorAbstract;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\PostRector\Collector\NodesToRemoveCollector;

final class NopVisitor extends NodeVisitorAbstract
{
    public function __construct(
        private readonly NodesToRemoveCollector $nodesToRemoveCollector
    ) {
    }

    public function enterNode(Node $node)
    {
        if (! $node instanceof StmtsAwareInterface && ! $node instanceof ClassLike) {
            return null;
        }

        if ($node->stmts === null) {
            return null;
        }

        $hasChanged = false;
        foreach ($node->stmts as $key => $stmt) {
            if (! $stmt instanceof Nop) {
                continue;
            }

            if (! isset($node->stmts[$key - 1])) {
                continue;
            }

            $previousEndLine = $node->stmts[$key - 1]->getEndLine();
            $endLine = $stmt->getEndLine();

            if ($previousEndLine !== $endLine) {
                continue;
            }

            if ($this->nodesToRemoveCollector->isNodeRemoved($node->stmts[$key - 1])) {
                unset($node->stmts[$key]);
                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }
}
