<?php

declare(strict_types=1);

namespace Rector\FileSystemRector\Parser;

use PhpParser\Node\Stmt;
use Rector\Core\PhpParser\NodeTraverser\FileWithoutNamespaceNodeTraverser;
use Rector\Core\PhpParser\Parser\RectorParser;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;
use Rector\Testing\Fixture\FixtureTempFileDumper;
use Symplify\SmartFileSystem\SmartFileInfo;

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
    public function parseFileInfoToNodesAndDecorate(SmartFileInfo|string $smartFileInfo): array
    {
        if (is_string($smartFileInfo)) {
            $smartFileInfo = FixtureTempFileDumper::dump($smartFileInfo);
        }

        $stmts = $this->rectorParser->parseFile($smartFileInfo);
        $stmts = $this->fileWithoutNamespaceNodeTraverser->traverse($stmts);

        $file = new File($smartFileInfo, $smartFileInfo->getContents());

        return $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($file, $stmts);
    }
}
