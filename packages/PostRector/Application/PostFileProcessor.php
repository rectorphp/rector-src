<?php

declare(strict_types=1);

namespace Rector\PostRector\Application;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use Rector\PostRector\Contract\Rector\PostRectorInterface;
use Rector\PostRector\Rector\NameImportingPostRector;
use Rector\PostRector\Rector\UnusedImportRemovingPostRector;
use Rector\PostRector\Rector\UseAddingPostRector;
use Rector\Skipper\Skipper\Skipper;

final class PostFileProcessor
{
    /**
     * @var PostRectorInterface[]
     */
    private array $postRectors = [];

    public function __construct(
        private readonly Skipper $skipper,
        // set order here
        UseAddingPostRector $useAddingPostRector,
        NameImportingPostRector $nameImportingPostRector,
        UnusedImportRemovingPostRector $unusedImportRemovingPostRector,
    ) {
        $this->postRectors = [
            // priority: 600
            $nameImportingPostRector,
            // priority: 500
            $useAddingPostRector,
            // priority: 100
            $unusedImportRemovingPostRector,
        ];
    }

    /**
     * @param Node[] $stmts
     * @return Node[]
     */
    public function traverse(array $stmts, string $filePath): array
    {
        foreach ($this->postRectors as $postRector) {
            if ($this->skipper->shouldSkipElementAndFilePath($postRector, $filePath)) {
                continue;
            }

            $nodeTraverser = new NodeTraverser();
            $nodeTraverser->addVisitor($postRector);
            $stmts = $nodeTraverser->traverse($stmts);
        }

        return $stmts;
    }
}
