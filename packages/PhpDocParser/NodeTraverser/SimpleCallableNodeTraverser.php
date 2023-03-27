<?php

declare(strict_types=1);

namespace Rector\PhpDocParser\NodeTraverser;

use PhpParser\Node;
use Rector\Core\PhpParser\NodeTraverser\CleanVisitorNodeTraverser;
use Rector\PhpDocParser\NodeVisitor\CallableNodeVisitor;

/**
 * @api
 */
final class SimpleCallableNodeTraverser
{
    public function __construct(private readonly CleanVisitorNodeTraverser $cleanVisitorNodeTraverser)
    {
    }

    /**
     * @param callable(Node $node): (int|Node|null) $callable
     * @param Node|Node[]|null $node
     */
    public function traverseNodesWithCallable(Node | array | null $node, callable $callable): void
    {
        if ($node === null) {
            return;
        }

        if ($node === []) {
            return;
        }

        $callableNodeVisitor = new CallableNodeVisitor($callable);
        $this->cleanVisitorNodeTraverser->addVisitor($callableNodeVisitor);

        $nodes = $node instanceof Node ? [$node] : $node;
        $this->cleanVisitorNodeTraverser->traverse($nodes);
    }
}
