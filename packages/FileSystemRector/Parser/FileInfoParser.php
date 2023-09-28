<?php

declare(strict_types=1);

namespace Rector\FileSystemRector\Parser;

use Nette\Utils\FileSystem;
use PhpParser\Node\Stmt;
use Rector\Core\PhpParser\Parser\RectorParser;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;

/**
 * Only for testing, @todo move to testing
 */
final class FileInfoParser
{
    public function __construct(
        private readonly NodeScopeAndMetadataDecorator $nodeScopeAndMetadataDecorator,
        private readonly RectorParser $rectorParser,
        private readonly CurrentFileProvider $currentFileProvider
    ) {
    }

    /**
     * @api tests only
     * @return Stmt[]
     */
    public function parseFileInfoToNodesAndDecorate(string $filePath): array
    {
        $fileContent = FileSystem::read($filePath);
        $stmts = $this->rectorParser->parseString($fileContent);

        $file = new File($filePath, $fileContent);
        $stmts = $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($file->getFilePath(), $stmts);

        $file->hydrateStmtsAndTokens($stmts, $stmts, []);
        $this->currentFileProvider->setFile($file);

        return $stmts;
    }
}
