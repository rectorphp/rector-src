<?php

declare(strict_types=1);

namespace Rector\NodeRemoval;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use Rector\ChangesReporting\Collector\RectorChangeCollector;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PostRector\Collector\NodesToRemoveCollector;

final class NodeRemover
{
    public function __construct(
        private readonly NodesToRemoveCollector $nodesToRemoveCollector,
        private readonly RectorChangeCollector $rectorChangeCollector
    ) {
    }

    /**
     * @deprecated Return NodeTraverser::* to remove node directly instead
     */
    public function removeNode(Node $node): void
    {
        // this make sure to keep just added nodes, e.g. added class constant, that doesn't have analysis of full code in this run
        // if this is missing, there are false positive e.g. for unused private constant
        $isJustAddedNode = ! (bool) $node->getAttribute(AttributeKey::ORIGINAL_NODE);
        if ($isJustAddedNode) {
            return;
        }

        $this->nodesToRemoveCollector->addNodeToRemove($node);
        $this->rectorChangeCollector->notifyNodeFileInfo($node);
    }

    /**
     * @param Node[] $nodes
     */
    public function removeNodes(array $nodes): void
    {
        foreach ($nodes as $node) {
            $this->removeNode($node);
        }
    }

    public function removeArg(FuncCall | MethodCall | StaticCall $node, int $key): void
    {
        if ($node->getArgs() === []) {
            throw new ShouldNotHappenException();
        }

        // already removed
        if (! isset($node->args[$key])) {
            return;
        }

        $this->removeNode($node->args[$key]);
        unset($node->args[$key]);
    }

    /**
     * @api phpunit
     */
    public function removeStmt(Closure | ClassMethod | Function_ $functionLike, int $key): void
    {
        if ($functionLike->stmts === null) {
            throw new ShouldNotHappenException();
        }

        // already removed
        if (! isset($functionLike->stmts[$key])) {
            return;
        }

        $this->removeNode($functionLike->stmts[$key]);
        unset($functionLike->stmts[$key]);
    }
}
