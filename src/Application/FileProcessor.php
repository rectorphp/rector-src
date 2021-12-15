<?php

declare(strict_types=1);

namespace Rector\Core\Application;

use PHPStan\Parser\ParserErrorsException;
use Rector\ChangesReporting\Collector\AffectedFilesCollector;
use Rector\Core\PhpParser\NodeTraverser\RectorNodeTraverser;
use Rector\Core\PhpParser\Parser\RectorParser;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;

final class FileProcessor
{
    public function __construct(
        private readonly AffectedFilesCollector $affectedFilesCollector,
        private readonly NodeScopeAndMetadataDecorator $nodeScopeAndMetadataDecorator,
        private readonly RectorParser $rectorParser,
        private readonly RectorNodeTraverser $rectorNodeTraverser
    ) {
    }

    public function parseFileInfoToLocalCache(File $file): void
    {
        // store tokens by absolute path, so we don't have to print them right now
        $smartFileInfo = $file->getSmartFileInfo();
        try {
            $stmtsAndTokens = $this->rectorParser->parseFileToStmtsAndTokens($smartFileInfo);
        } catch (ParserErrorsException $parserErrorsException) {
            // detect if FN token error
            $stmtsAndTokens = $this->rectorParser->parseFileToStmtsAndTokensWithPHP73($smartFileInfo);
        }

        $oldStmts = $stmtsAndTokens->getStmts();
        $oldTokens = $stmtsAndTokens->getTokens();

        // @todo may need tweak to refresh PHPStan types to avoid issue like in https://github.com/rectorphp/rector/issues/6561
        $newStmts = $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($file, $oldStmts);
        $file->hydrateStmtsAndTokens($newStmts, $oldStmts, $oldTokens);
    }

    /**
     * @return mixed[]
     */
    public function refactor(File $file, Configuration $configuration): array
    {
        $newStmts = $this->rectorNodeTraverser->traverse($file->getNewStmts());
        $file->changeNewStmts($newStmts);

        $this->affectedFilesCollector->removeFromList($file);
        while ($otherTouchedFile = $this->affectedFilesCollector->getNext()) {
            $this->refactor($otherTouchedFile, $configuration);
        }

        // @todo parallel - to be implemented
        return [];
    }
}
