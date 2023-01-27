<?php

declare(strict_types=1);

namespace Rector\Comments\NodeDocBlock;

use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\Printer\PhpDocInfoPrinter;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class DocBlockUpdater
{
    public function __construct(
        private readonly PhpDocInfoPrinter $phpDocInfoPrinter
    ) {
    }

    public function updateNodeWithPhpDocInfo(Stmt $stmt): void
    {
        // nothing to change? don't save it
        $phpDocInfo = $this->resolveChangedPhpDocInfo($stmt);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return;
        }

        $phpDoc = $this->printPhpDocInfoToString($phpDocInfo);

        // make sure, that many separated comments are not removed
        if ($phpDoc === '') {
            $this->setCommentsAttribute($stmt);

            return;
        }

        // this is needed to remove duplicated // commentsAsText
        $stmt->setDocComment(new Doc($phpDoc));
    }

    public function updateRefactoredNodeWithPhpDocInfo(Node $node): void
    {
        // nothing to change? don't save it
        $phpDocInfo = $this->resolveChangedPhpDocInfo($node);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return;
        }

        $phpDocNode = $phpDocInfo->getPhpDocNode();
        if ($phpDocNode->children === []) {
            $this->setCommentsAttribute($node);
            return;
        }

        $node->setDocComment(new Doc((string) $phpDocNode));
    }

    private function setCommentsAttribute(Node $node): void
    {
        if ($node->hasAttribute(AttributeKey::PREVIOUS_DOCS_AS_COMMENTS)) {
            /** @var Comment[] $previousDocsAsComments */
            $previousDocsAsComments = $node->getAttribute(AttributeKey::PREVIOUS_DOCS_AS_COMMENTS);
            $node->setAttribute(AttributeKey::COMMENTS, $previousDocsAsComments);
        }

        if ($node->hasAttribute(AttributeKey::NEW_MAIN_DOC)) {
            /** @var Doc $newMainDoc */
            $newMainDoc = $node->getAttribute(AttributeKey::NEW_MAIN_DOC);
            $node->setDocComment($newMainDoc);
        }

        $comments = array_filter($node->getComments(), static fn (Comment $comment): bool => ! $comment instanceof Doc);
        $node->setAttribute(AttributeKey::COMMENTS, $comments);
    }

    private function resolveChangedPhpDocInfo(Node $node): ?PhpDocInfo
    {
        $phpDocInfo = $node->getAttribute(AttributeKey::PHP_DOC_INFO);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return null;
        }

        if (! $phpDocInfo->hasChanged()) {
            return null;
        }

        return $phpDocInfo;
    }

    private function printPhpDocInfoToString(PhpDocInfo $phpDocInfo): string
    {
        if ($phpDocInfo->isNewNode()) {
            return $this->phpDocInfoPrinter->printNew($phpDocInfo);
        }

        return $this->phpDocInfoPrinter->printFormatPreserving($phpDocInfo);
    }
}
