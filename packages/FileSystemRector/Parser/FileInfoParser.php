<?php

declare(strict_types=1);

namespace Rector\FileSystemRector\Parser;

use Nette\Utils\FileSystem;
use PhpParser\Node\Stmt;
use Rector\Core\PhpParser\NodeTraverser\FileWithoutNamespaceNodeTraverser;
use Rector\Core\PhpParser\Parser\RectorParser;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;

/**
 * Only for testing, @todo move to testing
 */
final class FileInfoParser
{
    public function __construct(
        private readonly NodeScopeAndMetadataDecorator $nodeScopeAndMetadataDecorator,
        private readonly FileWithoutNamespaceNodeTraverser $fileWithoutNamespaceNodeTraverser,
        private readonly RectorParser $rectorParser
    ) {
    }

    /**
     * @return Stmt[]
     */
    public function parseFileInfoToNodesAndDecorate(string $filePath): array
    {
        $stmts = $this->rectorParser->parseFile($filePath);
        $stmts = $this->fileWithoutNamespaceNodeTraverser->traverse($stmts);

        $file = new File($filePath, FileSystem::read($filePath));

        return $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($file, $stmts);
    }
}
