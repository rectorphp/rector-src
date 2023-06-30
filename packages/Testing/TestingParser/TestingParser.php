<?php

declare(strict_types=1);

namespace Rector\Testing\TestingParser;

use Nette\Utils\FileSystem;
use PhpParser\Node;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\ParameterProvider;
use Rector\Core\PhpParser\Parser\RectorParser;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;

/**
 * @api
 */
final class TestingParser
{
    public function __construct(
        private readonly RectorParser $rectorParser,
        private readonly NodeScopeAndMetadataDecorator $nodeScopeAndMetadataDecorator,
        private readonly CurrentFileProvider $currentFileProvider
    ) {
    }

    public function parseFilePathToFile(string $filePath): File
    {
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
        ParameterProvider::setParameter(Option::SOURCE, [$filePath]);

        $stmts = $this->rectorParser->parseFile($filePath);

        $file = new File($filePath, FileSystem::read($filePath));

        $stmts = $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($file, $stmts);
        $file->hydrateStmtsAndTokens($stmts, $stmts, []);

        $this->currentFileProvider->setFile($file);

        // reset, as parameters are static
        ParameterProvider::setParameter(Option::SOURCE, []);

        return $stmts;
    }
}
