<?php

declare(strict_types=1);

namespace Rector\PhpDocParser\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitorAbstract;

final class CallableNodeVisitor extends NodeVisitorAbstract
{
    /**
     * @var callable(Node): (int|Node|null)
     */
    private $callable;

    /**
     * @var \PhpParser\Node[]
     */
    private ?array $newArrayStmts = null;

    /**
     * @param callable(Node $node): (int|Node|null|array) $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function enterNode(Node $node)
    {
        $originalNode = $node;

        $callable = $this->callable;

        /** @var int|Node|null|Stmt[] $newNode */
        $newNode = $callable($node);

        if (is_array($newNode)) {
            $this->newArrayStmts = $newNode;

            return $node;
        }

<<<<<<< HEAD
<<<<<<< HEAD
=======
        // @todo remove
>>>>>>> 55e0d49081 (misc)
//        if ($originalNode instanceof Stmt && $newNode instanceof Expr) {
//            return new Expression($newNode);
//        }
=======
        // @todo remove
        //        if ($originalNode instanceof Stmt && $newNode instanceof Expr) {
        //            return new Expression($newNode);
        //        }
>>>>>>> 38ed2fd606 (fixup! misc)

        return $newNode;
    }

    public function leaveNode(Node $node)
    {
        if ($this->newArrayStmts !== null) {
            $newArrayStmts = $this->newArrayStmts;
            $this->newArrayStmts = null;

            return $newArrayStmts;
        }

        return $node;
        //return parent::leaveNode($node);
    }
}
