<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\Comparing;

use PhpParser\Node;
use Rector\Comments\CommentRemover;
use Rector\Core\Contract\PhpParser\NodePrinterInterface;

final class NodeComparator
{
    public function __construct(
        private readonly CommentRemover $commentRemover,
        private readonly NodePrinterInterface $nodePrinter
    ) {
    }

    /**
     * Removes all comments from both nodes
     * @param Node|Node[]|null $node
     */
    public function printWithoutComments(Node | array | null $node): string
    {
        $nodeWithoutComment = $this->commentRemover->removeFromNode($node);
        $content = $this->nodePrinter->print($nodeWithoutComment);

        return trim($content);
    }

    /**
     * @param Node|Node[]|null $firstNode
     * @param Node|Node[]|null $secondNode
     */
    public function areNodesEqual(Node | array | null $firstNode, Node | array | null $secondNode): bool
    {
        return $this->printWithoutComments($firstNode) === $this->printWithoutComments($secondNode);
    }

    /**
     * @param Node[] $availableNodes
     */
    public function isNodeEqual(Node $singleNode, array $availableNodes): bool
    {
        foreach ($availableNodes as $availableNode) {
            if ($this->areNodesEqual($singleNode, $availableNode)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks even clone nodes
     */
    public function areSameNode(Node $firstNode, Node $secondNode): bool
    {
        if ($firstNode === $secondNode) {
            return true;
        }

        if ($firstNode->getStartTokenPos() !== $secondNode->getStartTokenPos()) {
            return false;
        }

        if ($firstNode->getEndTokenPos() !== $secondNode->getEndTokenPos()) {
            return false;
        }

        $firstClass = $firstNode::class;
        $secondClass = $secondNode::class;

        return $firstClass === $secondClass;
    }
}
