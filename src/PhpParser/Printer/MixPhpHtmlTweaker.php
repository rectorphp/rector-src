<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\Printer;

use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt;
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
     * @param Stmt[] $nodesToAddAfter
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
        if (! $stmt instanceof Stmt) {
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

        $firstNodeAfterNode->setAttribute(AttributeKey::ORIGINAL_NODE, null);
        $this->nodeRemover->removeNode($node);
    }
}
