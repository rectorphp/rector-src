<?php

declare(strict_types=1);

namespace Rector\Testing\TestingParser;

use Nette\Utils\FileSystem;
use PhpParser\Node;
use Rector\Application\Provider\CurrentFileProvider;
use Rector\CodingStyle\ClassNameImport\UsedImportsResolver;
use Rector\CodingStyle\ClassNameImport\ValueObject\UsedImports;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;
use Rector\NodeTypeResolver\Reflection\BetterReflection\SourceLocatorProvider\DynamicSourceLocatorProvider;
use Rector\PhpParser\Node\FileNode;
use Rector\PhpParser\Parser\RectorParser;
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
        private UsedImportsResolver $usedImportsResolver,
    ) {
    }

    public function parseFilePathToFile(string $filePath): File
    {
        [$file, $stmts] = $this->parseToFileAndStmts($filePath);
        return $file;
    }

    /**
     * @return Node[]
     */
    public function parseFileToDecoratedNodes(string $filePath): array
    {
        [$file, $stmts] = $this->parseToFileAndStmts($filePath);
        return $stmts;
    }

    /**
     * @return array{0: File, 1: Node[]}
     */
    private function parseToFileAndStmts(string $filePath): array
    {
        // needed for PHPStan reflection, as it caches the last processed file
        $this->dynamicSourceLocatorProvider->setFilePath($filePath);

        $fileContent = FileSystem::read($filePath);
        $file = new File($filePath, $fileContent);
        $stmts = $this->rectorParser->parseString($fileContent);

        // wrap in FileNode to enable file-level rules
        $stmts = [new FileNode($stmts, new UsedImports([], [], []))];
        $stmts = $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($filePath, $stmts);

        // seed used imports once, after decoration when namespaced names are resolvable
        $fileNode = $stmts[0] ?? null;
        if ($fileNode instanceof FileNode) {
            $fileNode->setUsedImports($this->usedImportsResolver->resolveForStmts($fileNode->stmts));
        }

        $file->hydrateStmtsAndTokens($stmts, $stmts, []);
        $this->currentFileProvider->setFile($file);

        return [$file, $stmts];
    }
}
