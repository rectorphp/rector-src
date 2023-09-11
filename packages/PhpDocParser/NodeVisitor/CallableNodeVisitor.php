<?php

declare(strict_types=1);

namespace Rector\PhpDocParser\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;

final class CallableNodeVisitor extends NodeVisitorAbstract
{
    /**
     * @var callable(Node): (int|Node|null)
     */
    private $callable;

    private ?int $nodeIdToRemove = null;

    /**
     * @param callable(Node $node): (int|Node|null) $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function enterNode(Node $node): int|Node|null
    {
        $originalNode = $node;

        $callable = $this->callable;

        /** @var int|Node|null $newNode */
        $newNode = $callable($node);

        if ($newNode === NodeTraverser::REMOVE_NODE) {
            $this->nodeIdToRemove = spl_object_id($originalNode);
            return $originalNode;
        }

        if ($originalNode instanceof Stmt && $newNode instanceof Expr) {
            return new Expression($newNode);
        }

        return $newNode;
    }

    public function leaveNode(Node $node): int|Node
    {
        if ($this->nodeIdToRemove !== null
            && $this->nodeIdToRemove === spl_object_id($node)
        ) {
            $this->nodeIdToRemove = null;
            return NodeTraverser::REMOVE_NODE;
        }

        return $node;
    }
}
