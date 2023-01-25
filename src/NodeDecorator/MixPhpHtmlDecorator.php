<?php

declare(strict_types=1);

namespace Rector\Core\NodeDecorator;

use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\InlineHTML;
use PhpParser\Node\Stmt\Nop;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeRemoval\NodeRemover;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * Mix PHP+HTML decorator, which require reprint the InlineHTML
 * which is the safe it to next/prev Node has open and close php tag
 */
final class MixPhpHtmlDecorator
{
    public function __construct(
        private readonly NodeRemover $nodeRemover,
        private readonly NodeComparator $nodeComparator,
        private readonly CurrentFileProvider $currentFileProvider
    ) {
    }

    public function decorateFileWithoutNamespace(FileWithoutNamespace $fileWithoutNamespace): void
    {
        if (! $fileWithoutNamespace->stmts[1] instanceof InlineHTML) {
            return;
        }

        if ($fileWithoutNamespace->stmts[0] instanceof InlineHTML) {
            return;
        }

        $file = $this->currentFileProvider->getFile();
        if (! $file instanceof File) {
            return;
        }

        $oldTokens = $file->getOldTokens();
        $endTokenPost = $fileWithoutNamespace->stmts[0]->getEndTokenPos();

        // No token end? Just added
        if (! isset($oldTokens[$endTokenPost])) {
            $fileWithoutNamespace->stmts[1]->setAttribute(AttributeKey::ORIGINAL_NODE, null);
        }
    }

    public function decorateNextNode(Node $node, InlineHTML $inlineHTML): void
    {
        $file = $this->currentFileProvider->getFile();
        if (! $file instanceof File) {
            return;
        }

        $oldTokens = $file->getOldTokens();
        $endTokenPost = $node->getEndTokenPos();

        if (isset($oldTokens[$endTokenPost])) {
            return;
        }

        // No token end? Just added
        $inlineHTML->setAttribute(AttributeKey::ORIGINAL_NODE, null);
    }

    public function decorateBefore(Node $node, Node $previousNode = null): void
    {
        if ($previousNode instanceof InlineHTML && ! $node instanceof InlineHTML) {
            $previousNode->setAttribute(AttributeKey::ORIGINAL_NODE, null);
            return;
        }

        if ($node instanceof InlineHTML && ! $previousNode instanceof Node) {
            $node->setAttribute(AttributeKey::ORIGINAL_NODE, null);
        }
    }

    /**
     * @param Node[] $nodes
     */
    public function decorateAfter(Node $node, array $nodes): void
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

        $stmt = $this->resolveAppendAfterNode($node, $nodes);
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

        $firstNodeAfterNode->setAttribute(AttributeKey::ORIGINAL_NODE, null);

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
