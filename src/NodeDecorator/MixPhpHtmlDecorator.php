<?php

declare(strict_types=1);

namespace Rector\Core\NodeDecorator;

use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\InlineHTML;
use PhpParser\Node\Stmt\Nop;
use Rector\NodeRemoval\NodeRemover;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Mix PHP+HTML decorator, which require reprint the InlineHTML
 * which is the safe way to make next/prev Node has open and close php tag
 */
final class MixPhpHtmlDecorator
{
    private NodeRemover $nodeRemover;

    #[Required]
    public function autowire(NodeRemover $nodeRemover): void
    {
        $this->nodeRemover = $nodeRemover;
    }

    /**
     * @param Stmt[] $stmts
     */
    public function decorateInlineHTML(InlineHTML $inlineHTML, int $key, array $stmts): void
    {
        if (isset($stmts[$key - 1]) && ! $stmts[$key - 1] instanceof InlineHTML) {
            $this->rePrintInlineHTML($inlineHTML, $stmts[$key - 1]);
        }
        if (! isset($stmts[$key + 1])) {
            return;
        }
        if ($stmts[$key + 1] instanceof InlineHTML) {
            return;
        }
        $this->rePrintInlineHTML($inlineHTML, $stmts[$key + 1]);
    }

    /**
     * @param Stmt[] $stmts
     */
    public function decorateAfterNop(Node $node, int $key, array $stmts): void
    {
        if (! $node instanceof Nop) {
            return;
        }

        $currentNode = current($stmts);
        if ($currentNode !== $node) {
            return;
        }

        if (! isset($stmts[$key + 1]) || $stmts[$key + 1] instanceof InlineHTML) {
            return;
        }

        $firstNodeAfterNop = $stmts[$key + 1];
        if ($firstNodeAfterNop->getStartTokenPos() >= 0) {
            return;
        }

        // Token start = -1, just added
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

        $firstNodeAfterNop->setAttribute(AttributeKey::COMMENTS, $nodeComments);

        // remove Nop is marked  as comment of Next Node
        $this->nodeRemover->removeNode($node);
    }

    private function rePrintInlineHTML(InlineHTML $inlineHTML, Stmt $stmt): void
    {
        // Token start = -1, just added
        if ($stmt->getStartTokenPos() <= 0) {
            $inlineHTML->setAttribute(AttributeKey::ORIGINAL_NODE, null);
        }
    }
}
