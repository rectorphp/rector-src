<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\Printer;

use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\InlineHTML;
use PhpParser\Node\Stmt\Nop;
use Rector\NodeRemoval\NodeRemover;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class MixPhpHtmlTweaker
{
    public function __construct(
        private readonly NodeRemover $nodeRemover
    ) {
    }

    public function before(Node $node): void
    {
        $firstNodePreviousNode = $node->getAttribute(AttributeKey::PREVIOUS_NODE);
        if ($firstNodePreviousNode instanceof InlineHTML && ! $node instanceof InlineHTML) {
            // re-print InlineHTML is safe
            $firstNodePreviousNode->setAttribute(AttributeKey::ORIGINAL_NODE, null);
        }
    }

    /**
     * @param Node[]|null[] $nodesToAddAfter
     */
    public function after(Node $node, array $nodesToAddAfter): void
    {
        if (! $node instanceof Nop) {
            return;
        }

        $firstNodeAfterNode = $node->getAttribute(AttributeKey::NEXT_NODE);
        if (! $firstNodeAfterNode instanceof Node) {
            return;
        }

        if (! $firstNodeAfterNode instanceof InlineHTML) {
            return;
        }

        $stmt = current($nodesToAddAfter);
        if (! $stmt instanceof Node) {
            return;
        }

        if ($stmt instanceof InlineHTML) {
            return;
        }

        $nodeComments = [];
        foreach ($node->getComments() as $comment) {
            if ($comment instanceof Doc) {
                $nodeComments[] = new Comment(
                    $comment->getText(),
                    $comment->getStartLine(),
                    $comment->getStartFilePos(),
                    $comment->getStartTokenPos(),
                    $comment->getEndLine(),
                    $comment->getEndFilePos(),
                    $comment->getEndTokenPos()
                );
                continue;
            }

            $nodeComments[] = $comment;
        }

        $stmt->setAttribute(AttributeKey::COMMENTS, $nodeComments);

        // re-print InlineHTML is safe
        $firstNodeAfterNode->setAttribute(AttributeKey::ORIGINAL_NODE, null);

        // remove Nop is marked  as comment of Next Node
        $this->nodeRemover->removeNode($node);
    }
}
