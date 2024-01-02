<?php

declare(strict_types=1);

namespace Rector\Comments;

use PhpParser\Node;
use Rector\Comments\NodeTraverser\CommentRemovingNodeTraverser;

/**
 * @see \Rector\Tests\Comments\CommentRemover\CommentRemoverTest
 */
final readonly class CommentRemover
{
    public function __construct(
        private CommentRemovingNodeTraverser $commentRemovingNodeTraverser
    ) {
    }

    /**
     * @param Node[]|Node|null $node
     * @return Node[]|null
     */
    public function removeFromNode(array | Node | null $node): array | null
    {
        if ($node === null) {
            return null;
        }

        $nodes = is_array($node) ? $node : [$node];
        return $this->commentRemovingNodeTraverser->traverse($nodes);
    }
}
