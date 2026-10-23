<?php

declare(strict_types=1);

namespace Rector\PhpParser\Comparing;

use PhpParser\Node;
use Rector\Comments\CommentRemover;
use Rector\PhpParser\Printer\BetterStandardPrinter;

final readonly class NodeComparator
{
    public function __construct(
        private CommentRemover $commentRemover,
        private BetterStandardPrinter $betterStandardPrinter
    ) {
    }

    /**
     * Removes all comments from both nodes
     * @param Node|Node[]|null $node
     */
    public function printWithoutComments(Node|array|null $node): string
    {
        $node = $this->commentRemover->removeFromNode($node);
        $content = $this->betterStandardPrinter->print($node);

        return trim($content);
    }

    public function areNodesEqual(Node|null $firstNode, Node|null $secondNode): bool
    {
        if (! $firstNode instanceof Node || ! $secondNode instanceof Node) {
            return false;
        }

        if ($firstNode::class !== $secondNode::class) {
            return false;
        }

        return $this->printWithoutComments($firstNode) === $this->printWithoutComments($secondNode);
    }

    /**
     * @param Node[] $availableNodes
     */
    public function isNodeEqual(Node $singleNode, array $availableNodes): bool
    {
        return array_any(
            $availableNodes,
            fn (Node|array|null $availableNode): bool => $this->areNodesEqual($singleNode, $availableNode)
        );
    }

    /**
     * Checks even clone nodes
     */
    public function areSameNode(Node $firstNode, Node $secondNode): bool
    {
        if ($firstNode === $secondNode) {
            return true;
        }

        $firstClass = $firstNode::class;
        $secondClass = $secondNode::class;

        if ($firstClass !== $secondClass) {
            return false;
        }

        if ($firstNode->getStartTokenPos() !== $secondNode->getStartTokenPos()) {
            return false;
        }

        if ($firstNode->getEndTokenPos() !== $secondNode->getEndTokenPos()) {
            return false;
        }

        $printFirstNode = $this->betterStandardPrinter->print($firstNode);
        $printSecondNode = $this->betterStandardPrinter->print($secondNode);

        return $printFirstNode === $printSecondNode;
    }
}
