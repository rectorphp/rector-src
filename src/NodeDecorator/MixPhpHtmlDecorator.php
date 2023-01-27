<?php

declare(strict_types=1);

namespace Rector\Core\NodeDecorator;

use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\InlineHTML;
use PhpParser\Node\Stmt\Nop;
use Rector\Core\PhpParser\Comparing\NodeComparator;
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

    private NodeComparator $nodeComparator;

    #[Required]
    public function autowire(NodeRemover $nodeRemover, NodeComparator $nodeComparator): void
    {
        $this->nodeRemover = $nodeRemover;
        $this->nodeComparator = $nodeComparator;
    }

    /**
     * @param Stmt[] $stmts
     */
    public function decorateInlineHTML(InlineHTML $inlineHTML, int $key, array $stmts)
    {
        if (isset($stmts[$key - 1]) && ! $stmts[$key - 1] instanceof InlineHTML) {
            $stmt = $stmts[$key - 1];
            if ($stmt->getStartTokenPos() <= 0) {
                $inlineHTML->setAttribute(AttributeKey::ORIGINAL_NODE, null);
            }
        }

        if (isset($stmts[$key + 1]) && ! $stmts[$key + 1] instanceof InlineHTML) {
            $stmt = $stmts[$key + 1];
            if ($stmt->getStartTokenPos() <= 0) {
                $inlineHTML->setAttribute(AttributeKey::ORIGINAL_NODE, null);
            }
        }
    }

    /**
     * @param Node[] $nodes
     */
    public function decorateAfterNop(Node $node, int $key, array $nodes): void
    {
        if (! $node instanceof Nop) {
            return;
        }

        $currentNode = current($nodes);
        if ($currentNode !== $node) {
            return;
        }

        if (! isset($nodes[$key + 1]) || $nodes[$key + 1] instanceof InlineHTML) {
            return;
        }

        $firstNodeAfterNop = $nodes[$key + 1];
        if (! $firstNodeAfterNop instanceof Stmt) {
            return;
        }

        if ($firstNodeAfterNop->getStartTokenPos() >= 0) {
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

        $firstNodeAfterNop->setAttribute(AttributeKey::COMMENTS, $nodeComments);

        // remove Nop is marked  as comment of Next Node
        $this->nodeRemover->removeNode($node);
    }

    /**
     * @param Node[] $nodes
     */
    private function resolveAppendAfterNode(Nop $nop, array $nodes): ?Node
    {
        foreach ($nodes as $key => $subNode) {
            if (! $this->nodeComparator->areSameNode($subNode, $nop)) {
                continue;
            }

            if (! isset($nodes[$key + 1])) {
                continue;
            }

            return $nodes[$key + 1];
        }

        return null;
    }
}
