<?php

declare(strict_types=1);

namespace Rector\PostRector\Collector;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\MutatingScope;
use Rector\ChangesReporting\Collector\RectorChangeCollector;
use Rector\Core\Application\ChangedNodeScopeRefresher;
use Rector\Core\Contract\PhpParser\NodePrinterInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PostRector\Contract\Collector\NodeCollectorInterface;

final class NodesToAddCollector implements NodeCollectorInterface
{
    /**
     * @var Stmt[][]
     */
    private array $nodesToAddAfter = [];

    /**
     * @var Stmt[][]
     */
    private array $nodesToAddBefore = [];

    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly RectorChangeCollector $rectorChangeCollector,
        private readonly NodePrinterInterface $nodePrinter,
        private readonly ChangedNodeScopeRefresher $changedNodeScopeRefresher
    ) {
    }

    public function isActive(): bool
    {
        return $this->nodesToAddAfter !== [] || $this->nodesToAddBefore !== [];
    }

    /**
     * @deprecated Return created nodes right in refactor() method to keep context instead.
     */
    public function addNodeBeforeNode(Node $addedNode, Node $positionNode): void
    {
        if ($positionNode->getAttributes() === []) {
            $message = sprintf('Switch arguments in "%s()" method', __METHOD__);
            throw new ShouldNotHappenException($message);
        }

        /** @var MutatingScope|null $currentScope */
        $currentScope = $positionNode->getAttribute(AttributeKey::SCOPE);
        $this->changedNodeScopeRefresher->refresh($addedNode, $currentScope);

        $position = $this->resolveNearestStmtPosition($positionNode);
        $this->nodesToAddBefore[$position][] = $this->wrapToExpression($addedNode);

        $this->rectorChangeCollector->notifyNodeFileInfo($positionNode);
    }

    /**
     * @api
     * @param Node[] $addedNodes
     * @deprecated Return created nodes right in refactor() method to keep context instead.
     */
    public function addNodesAfterNode(array $addedNodes, Node $positionNode): void
    {
        foreach ($addedNodes as $addedNode) {
            // prevent fluent method weird indent
            $addedNode->setAttribute(AttributeKey::ORIGINAL_NODE, null);
            $this->addNodeAfterNode($addedNode, $positionNode);
        }

        $this->rectorChangeCollector->notifyNodeFileInfo($positionNode);
    }

    /**
     * In most case, it's better return created nodes right in refactor() method to keep context
     * @internal
     */
    public function addNodeAfterNode(Node $addedNode, Node $positionNode): void
    {
        if ($positionNode->getAttributes() === []) {
            $message = sprintf('Switch arguments in "%s()" method', __METHOD__);
            throw new ShouldNotHappenException($message);
        }

        /** @var MutatingScope|null $currentScope */
        $currentScope = $positionNode->getAttribute(AttributeKey::SCOPE);

        $this->changedNodeScopeRefresher->refresh($addedNode, $currentScope);

        $position = $this->resolveNearestStmtPosition($positionNode);
        $this->nodesToAddAfter[$position][] = $this->wrapToExpression($addedNode);

        $this->rectorChangeCollector->notifyNodeFileInfo($positionNode);
    }

    /**
     * @return Stmt[]
     */
    public function getNodesToAddAfterNode(Node $node): array
    {
        $position = spl_object_hash($node);
        return $this->nodesToAddAfter[$position] ?? [];
    }

    /**
     * @return Stmt[]
     */
    public function getNodesToAddBeforeNode(Node $node): array
    {
        $position = spl_object_hash($node);
        return $this->nodesToAddBefore[$position] ?? [];
    }

    public function clearNodesToAddAfter(Node $node): void
    {
        $objectHash = spl_object_hash($node);
        unset($this->nodesToAddAfter[$objectHash]);
    }

    public function clearNodesToAddBefore(Node $node): void
    {
        $objectHash = spl_object_hash($node);
        unset($this->nodesToAddBefore[$objectHash]);
    }

    /**
     * @api downgrade
     * @deprecated Return created nodes right in refactor() method to keep context instead.
     * @param Node[] $newNodes
     */
    public function addNodesBeforeNode(array $newNodes, Node $positionNode): void
    {
        foreach ($newNodes as $newNode) {
            $this->addNodeBeforeNode($newNode, $positionNode);
        }

        $this->rectorChangeCollector->notifyNodeFileInfo($positionNode);
    }

    private function resolveNearestStmtPosition(Node $node): string
    {
        if ($node instanceof Stmt) {
            return spl_object_hash($node);
        }

        $currentStmt = $this->betterNodeFinder->resolveCurrentStatement($node);

        if ($currentStmt instanceof Stmt) {
            return spl_object_hash($currentStmt);
        }

        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
        if ($parentNode instanceof Return_) {
            return spl_object_hash($parentNode);
        }

        $foundStmt = $this->betterNodeFinder->findParentType($node, Stmt::class);

        if (! $foundStmt instanceof Stmt) {
            $printedNode = $this->nodePrinter->print($node);
            $errorMessage = sprintf('Could not find parent Stmt of "%s" node', $printedNode);
            throw new ShouldNotHappenException($errorMessage);
        }

        return spl_object_hash($foundStmt);
    }

    private function wrapToExpression(Expr | Stmt $node): Stmt
    {
        return $node instanceof Stmt ? $node : new Expression($node);
    }
}
