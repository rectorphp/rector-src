<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\NodeVisitor;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * Visitor that connects a child node to its parent node
 * as well as its sibling nodes, with verify previous node is not equal to current node.
 *
 * inspired by https://github.com/nikic/PHP-Parser/blob/0ffddce52d816f72d0efc4d9b02e276d3309ef01/lib/PhpParser/NodeVisitor/NodeConnectingVisitor.php
 */
final class NodeConnectingVisitor extends NodeVisitorAbstract
{
    /**
     * @var Node[]
     */
    private array $stack = [];

    private ?Node $node = null;

    public function beforeTraverse(array $nodes)
    {
        $this->stack = [];
        $this->node = null;

        return null;
    }

    public function enterNode(Node $node)
    {
        if ($this->stack !== []) {
            $node->setAttribute(AttributeKey::PARENT_NODE, $this->stack[count($this->stack) - 1]);
        }

        if ($this->node instanceof Node
            && $this->node !== $node
            && $this->node->getAttribute(AttributeKey::PARENT_NODE) === $node->getAttribute(
                AttributeKey::PARENT_NODE
            )) {
            $node->setAttribute(AttributeKey::PREVIOUS_NODE, $this->node);
            $this->node->setAttribute(AttributeKey::NEXT_NODE, $node);
        }

        $this->stack[] = $node;

        return null;
    }

    public function leaveNode(Node $node)
    {
        $this->node = $node;

        array_pop($this->stack);

        return null;
    }
}
