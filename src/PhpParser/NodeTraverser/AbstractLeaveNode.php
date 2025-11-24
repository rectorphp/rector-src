<?php

declare(strict_types=1);

namespace Rector\PhpParser\NodeTraverser;

use PhpParser\Node;
use PhpParser\NodeVisitor;
use PhpParser\NodeVisitorAbstract;

/**
 * @internal
 */
abstract class AbstractLeaveNode extends NodeVisitorAbstract
{
    protected ?int $toBeRemovedNodeId = null;

    /**
     * @var array<int, Node[]>
     */
    protected array $nodesToReturn = [];

    /**
     * Replacing nodes in leaveNode() method avoids infinite recursion
     * see"infinite recursion" in https://github.com/nikic/PHP-Parser/blob/master/doc/component/Walking_the_AST.markdown
     *
     * @return NodeVisitor::REMOVE_NODE|Node|null|Node[]
     */
    final public function leaveNode(Node $node): int|Node|null|array
    {
        // nothing to change here
        if ($this->toBeRemovedNodeId === null && $this->nodesToReturn === []) {
            return null;
        }

        $objectId = spl_object_id($node);
        if ($this->toBeRemovedNodeId === $objectId) {
            $this->toBeRemovedNodeId = null;

            return NodeVisitor::REMOVE_NODE;
        }

        return $this->nodesToReturn[$objectId] ?? $node;
    }
}
