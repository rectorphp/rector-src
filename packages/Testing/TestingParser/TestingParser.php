<?php

declare(strict_types=1);

namespace Rector\Testing\TestingParser;

use Nette\Utils\FileSystem;
use PhpParser\Node;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;
use Rector\Core\PhpParser\Parser\RectorParser;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;
use Rector\NodeTypeResolver\Reflection\BetterReflection\SourceLocatorProvider\DynamicSourceLocatorProvider;

/**
 * @api
 */
final class TestingParser
{
    public function __construct(
        private readonly RectorParser $rectorParser,
        private readonly NodeScopeAndMetadataDecorator $nodeScopeAndMetadataDecorator,
        private readonly CurrentFileProvider $currentFileProvider,
        private readonly DynamicSourceLocatorProvider $dynamicSourceLocatorProvider,
    ) {
    }

    public function parseFilePathToFile(string $filePath): File
    {
        // needed for PHPStan reflection, as it caches the last processed file
        $this->dynamicSourceLocatorProvider->setFilePath($filePath);

        $file = new File($filePath, FileSystem::read($filePath));
        $stmts = $this->rectorParser->parseFile($filePath);

        $stmts = $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($file, $stmts);

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

        SimpleParameterProvider::setParameter(Option::SOURCE, [$filePath]);

        $stmts = $this->rectorParser->parseFile($filePath);
        $file = new File($filePath, FileSystem::read($filePath));

        $stmts = $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($file, $stmts);
        $file->hydrateStmtsAndTokens($stmts, $stmts, []);

        $this->currentFileProvider->setFile($file);

        return $stmts;
    }
}
