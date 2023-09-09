<?php

declare(strict_types=1);

namespace Rector\Core\Application;

use Rector\Core\PhpParser\NodeTraverser\RectorNodeTraverser;
use Rector\Core\PhpParser\Parser\RectorParser;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;
use Rector\PostRector\Application\PostFileProcessor;

final class FileProcessor
{
    public function __construct(
        private readonly NodeScopeAndMetadataDecorator $nodeScopeAndMetadataDecorator,
        private readonly RectorParser $rectorParser,
        private readonly RectorNodeTraverser $rectorNodeTraverser,
        private readonly PostFileProcessor $postFileProcessor,
    ) {
    }

    public function parseFileInfoToLocalCache(File $file): void
    {
        // store tokens by absolute path, so we don't have to print them right now
        $stmtsAndTokens = $this->rectorParser->parseFileToStmtsAndTokens($file->getFilePath());

        $oldStmts = $stmtsAndTokens->getStmts();
        $oldTokens = $stmtsAndTokens->getTokens();

        $newStmts = $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($file, $oldStmts);
        $file->hydrateStmtsAndTokens($newStmts, $oldStmts, $oldTokens);
    }

    public function refactor(File $file): void
    {
        $newStmts = $this->rectorNodeTraverser->traverse($file->getNewStmts());

        // apply post rectors
        $postNewStmts = $this->postFileProcessor->traverse($newStmts);

        // this is needed for new tokens added in "afterTraverse()"
        $file->changeNewStmts($postNewStmts);
    }
}
