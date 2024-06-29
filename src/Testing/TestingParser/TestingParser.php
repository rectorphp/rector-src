<?php

declare(strict_types=1);

namespace Rector\Testing\TestingParser;

use Nette\Utils\FileSystem;
use PhpParser\Node;
use Rector\Application\Provider\CurrentFileProvider;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;
use Rector\NodeTypeResolver\Reflection\BetterReflection\SourceLocatorProvider\DynamicSourceLocatorProvider;
use Rector\PhpParser\Parser\RectorParser;
use Rector\UseImports\Storage\FileStorage;
use Rector\ValueObject\Application\File;

/**
 * @api
 */
final readonly class TestingParser
{
    public function __construct(
        private RectorParser $rectorParser,
        private NodeScopeAndMetadataDecorator $nodeScopeAndMetadataDecorator,
        private CurrentFileProvider $currentFileProvider,
        private DynamicSourceLocatorProvider $dynamicSourceLocatorProvider,
        private FileStorage $fileStorage
    ) {
    }

    public function parseFilePathToFile(string $filePath): File
    {
        // needed for PHPStan reflection, as it caches the last processed file
        $this->dynamicSourceLocatorProvider->setFilePath($filePath);

        $fileContent = FileSystem::read($filePath);
        $file = new File($filePath, $fileContent);
        $stmts = $this->rectorParser->parseString($fileContent);

        $stmts = $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($filePath, $stmts);
        $this->fileStorage->addFile($filePath, $file);

        $file->hydrateStmtsAndTokens($stmts, $stmts, []);
        $this->currentFileProvider->setFile($file);

        return $file;
    }

    /**
     * @return Node[]
     */
    public function parseFileToDecoratedNodes(string $filePath): array
    {
        // needed for PHPStan reflection, as it caches the last processed file
        $this->dynamicSourceLocatorProvider->setFilePath($filePath);

        $fileContent = FileSystem::read($filePath);
        $stmts = $this->rectorParser->parseString($fileContent);
        $file = new File($filePath, $fileContent);

        $stmts = $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($filePath, $stmts);
        $file->hydrateStmtsAndTokens($stmts, $stmts, []);
        $this->fileStorage->addFile($filePath, $file);

        $this->currentFileProvider->setFile($file);

        return $stmts;
    }
}
