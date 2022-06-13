<?php

declare(strict_types=1);

namespace Rector\NodeRemoval;

use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\ChangesReporting\Collector\RectorChangeCollector;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PostRector\Collector\NodesToRemoveCollector;

final class NodeRemover
{
    public function __construct(
        private readonly NodesToRemoveCollector $nodesToRemoveCollector,
        private readonly RectorChangeCollector $rectorChangeCollector,
        private readonly PhpDocInfoFactory $phpDocInfoFactory
    ) {
    }

    public function removeNode(Node $node): void
    {
        // this make sure to keep just added nodes, e.g. added class constant, that doesn't have analysis of full code in this run
        // if this is missing, there are false positive e.g. for unused private constant
        $isJustAddedNode = ! (bool) $node->getAttribute(AttributeKey::ORIGINAL_NODE);
        if ($isJustAddedNode) {
            return;
        }

        if ($node instanceof Stmt) {
            /**
             * Check single line comment,eg:
             *
             *      // @phpstan-ignore-next-line
             */
            $comments = $node->getAttribute(AttributeKey::COMMENTS) ?? [];

            foreach ($comments as $comment) {
                if (! $comment instanceof Comment) {
                    continue;
                }

                if ($comment->getText() === '// @phpstan-ignore-next-line') {
                    return;
                }
            }

            # Check multi lines comments, eg:
            #
            #  /**
            #   * @phpstan-ignore-next-line
            #   */
            #
            $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
            if ($phpDocInfo->hasByName('phpstan-ignore-next-line')) {
                return;
            }
        }

        $this->nodesToRemoveCollector->addNodeToRemove($node);
        $this->rectorChangeCollector->notifyNodeFileInfo($node);
    }

    public function removeNodeFromStatements(
        Class_ | ClassMethod | Function_ $nodeWithStatements,
        Node $toBeRemovedNode
    ): void {
        foreach ((array) $nodeWithStatements->stmts as $key => $stmt) {
            if ($toBeRemovedNode !== $stmt) {
                continue;
            }

            unset($nodeWithStatements->stmts[$key]);
            break;
        }
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

    public function removeStmt(Closure | ClassMethod | Function_ $functionLike, int $key): void
    {
        if ($functionLike->stmts === null) {
            throw new ShouldNotHappenException();
        }

        // notify about remove node
        $this->rectorChangeCollector->notifyNodeFileInfo($functionLike->stmts[$key]);

        unset($functionLike->stmts[$key]);
    }

    public function removeParam(ClassMethod $classMethod, int | Param $keyOrParam): void
    {
        $key = $keyOrParam instanceof Param ? $keyOrParam->getAttribute(AttributeKey::PARAMETER_POSITION) : $keyOrParam;

        if ($classMethod->params === null) {
            throw new ShouldNotHappenException();
        }

        // already removed
        if (! isset($classMethod->params[$key])) {
            return;
        }

        // notify about remove node
        $this->rectorChangeCollector->notifyNodeFileInfo($classMethod->params[$key]);

        unset($classMethod->params[$key]);
    }

    public function removeArg(FuncCall | MethodCall | StaticCall $node, int $key): void
    {
        if ($node->args === null) {
            throw new ShouldNotHappenException();
        }

        // already removed
        if (! isset($node->args[$key])) {
            return;
        }

        // notify about remove node
        $this->rectorChangeCollector->notifyNodeFileInfo($node->args[$key]);

        unset($node->args[$key]);
    }

    public function removeImplements(Class_ $class, int $key): void
    {
        if ($class->implements === null) {
            throw new ShouldNotHappenException();
        }

        // notify about remove node
        $this->rectorChangeCollector->notifyNodeFileInfo($class->implements[$key]);

        unset($class->implements[$key]);
    }
}
