<?php

declare(strict_types=1);

namespace Rector\Comments;

use PhpParser\Node;
use Rector\Comments\NodeTraverser\CommentRemovingNodeTraverser;

/**
 * @see \Rector\Tests\Comments\CommentRemover\CommentRemoverTest
 */
final class CommentRemover
{
    public function __construct(
        private readonly CommentRemovingNodeTraverser $commentRemovingNodeTraverser
    ) {
    }

    /**
     * @return Node[]|null
     */
    public function removeFromNode(array | Node | null $node): array | null
    {
        if ($node === null) {
            return null;
        }

        $nodes = $node instanceof Node
            ? [$node]
            : $node;
        $copiedNodes = array_map(fn (Node $node): Node => clone $node, $nodes);

        return $this->commentRemovingNodeTraverser->traverse($copiedNodes);
    }
}
