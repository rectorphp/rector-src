<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\Comparing;

use PhpParser\Node;
use Rector\Comments\CommentRemover;
use Rector\Core\Contract\PhpParser\NodePrinterInterface;
use Webmozart\Assert\Assert;

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
        $node = $this->commentRemover->removeFromNode($node);
        $content = $this->nodePrinter->print($node);

        return trim($content);
    }

    /**
     * @param Node|Node[]|null $firstNode
     * @param Node|Node[]|null $secondNode
     */
    public function areNodesEqual(Node | array | null $firstNode, Node | array | null $secondNode): bool
    {
        if ($firstNode instanceof Node && ! $secondNode instanceof Node) {
            return false;
        }

        if (! $firstNode instanceof Node && $secondNode instanceof Node) {
            return false;
        }

        if (is_array($firstNode)) {
            if (! is_array($secondNode)) {
                return false;
            }

            Assert::allIsAOf($firstNode, Node::class);
        }

        if (is_array($secondNode)) {
            if (! is_array($firstNode)) {
                return false;
            }

            Assert::allIsAOf($secondNode, Node::class);
        }

        return $this->printWithoutComments($firstNode) === $this->printWithoutComments($secondNode);
    }

    /**
     * @api
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

        $printFirstNode = $this->nodePrinter->print($firstNode);
        $printSecondNode = $this->nodePrinter->print($secondNode);

        return $printFirstNode === $printSecondNode;
    }
}
