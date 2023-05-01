<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\NodeVisitor;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

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
    private $stack = [];

    /**
     * @var ?Node
     */
    private $previous;

    public function beforeTraverse(array $nodes) {
        $this->stack    = [];
        $this->previous = null;
    }

    public function enterNode(Node $node) {
        if (!empty($this->stack)) {
            $node->setAttribute('parent', $this->stack[count($this->stack) - 1]);
        }

        if (! in_array($this->previous, [null, $node], true) && $this->previous->getAttribute('parent') === $node->getAttribute('parent')) {
            $node->setAttribute('previous', $this->previous);
            $this->previous->setAttribute('next', $node);
        }

        $this->stack[] = $node;
    }

    public function leaveNode(Node $node) {
        $this->previous = $node;

        array_pop($this->stack);
    }
}
