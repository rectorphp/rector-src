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
    public function __construct(private readonly NodeRemover $nodeRemover)
    {
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
     * @param Node[] $nodesToAddAfter
     */
    public function after(Node $node, array $nodesToAddAfter): void
    {
        $stmt = current($nodesToAddAfter);
        $firstNodeAfterNode = $node->getAttribute(AttributeKey::NEXT_NODE);

        if (! $node instanceof Nop) {
            return;
        }

        if (! $firstNodeAfterNode instanceof InlineHTML) {
            return;
        }

        if ($stmt instanceof InlineHTML) {
            return;
        }

        // mark node as comment
        $nopComments = [];

        foreach ($node->getComments() as $comment) {
            if ($comment instanceof Doc) {
                $nopComments[] = new Comment(
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

            $nopComments[] = $comment;
        }

        $currentComments = $stmt->getComments();
        $stmt->setAttribute(AttributeKey::COMMENTS, array_merge($nopComments, $currentComments));

        $firstNodeAfterNode->setAttribute(AttributeKey::ORIGINAL_NODE, null);
        $this->nodeRemover->removeNode($node);
    }
}