<?php

declare(strict_types=1);

namespace Rector\PhpDocParser\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use Rector\PhpParser\NodeVisitor\AbstractLeaveNode;

final class CallableNodeVisitor extends AbstractLeaveNode
{
    /**
     * @var callable(Node): (int|Node|null|Node[])
     */
    private $callable;

    /**
     * @param callable(Node $node): (int|Node|null|Node[]) $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function enterNode(Node $node): int|Node|null
    {
        $originalNode = $node;

        $callable = $this->callable;

        /** @var int|Node|null|Node[] $newNode */
        $newNode = $callable($node);

        if ($newNode === NodeVisitor::REMOVE_NODE) {
            $this->toBeRemovedNodeId = spl_object_id($originalNode);
            return $originalNode;
        }

        if (is_array($newNode)) {
            $nodeId = spl_object_id($node);
            $this->nodesToReturn[$nodeId] = $newNode;

            return $node;
        }

        if ($originalNode instanceof Stmt && $newNode instanceof Expr) {
            return new Expression($newNode);
        }

        return $newNode;
    }
}
