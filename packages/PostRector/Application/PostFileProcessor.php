<?php

declare(strict_types=1);

namespace Rector\PostRector\Application;

<<<<<<< HEAD
<<<<<<< HEAD
use PhpParser\Node;
=======
>>>>>>> 8110026be6 (move PostFileProcessor to FileProcessor, as always should run together)
=======
use PhpParser\Node;
>>>>>>> 2d84200b97 (introduce FileProcessResult, to align with PHPStan parallel architecture conventions and avoid using array shapes)
use PhpParser\NodeTraverser;
use Rector\Core\Logging\CurrentRectorProvider;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\PostRector\Contract\Rector\PostRectorInterface;
use Rector\PostRector\Rector\ClassRenamingPostRector;
use Rector\PostRector\Rector\NameImportingPostRector;
use Rector\PostRector\Rector\UnusedImportRemovingPostRector;
use Rector\PostRector\Rector\UseAddingPostRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Skipper\Skipper\Skipper;

final class PostFileProcessor
{
    /**
     * @var PostRectorInterface[]
     */
    private array $postRectors = [];

    public function __construct(
        private readonly Skipper $skipper,
        private readonly CurrentFileProvider $currentFileProvider,
        private readonly CurrentRectorProvider $currentRectorProvider,
        // set order here
        UseAddingPostRector $useAddingPostRector,
        NameImportingPostRector $nameImportingPostRector,
        ClassRenamingPostRector $classRenamingPostRector,
        UnusedImportRemovingPostRector $unusedImportRemovingPostRector,
    ) {
        $this->postRectors = [
            // priority: 650
            $classRenamingPostRector,
            // priority: 600
            $nameImportingPostRector,
            // priority: 500
            $useAddingPostRector,
            // priority: 100
            $unusedImportRemovingPostRector,
        ];
    }

    /**
<<<<<<< HEAD
<<<<<<< HEAD
     * @param Node[] $stmts
     * @return Node[]
=======
     * @param \PhpParser\Node[] $stmts
     * @return \PhpParser\Node[]
>>>>>>> 8110026be6 (move PostFileProcessor to FileProcessor, as always should run together)
=======
     * @param Node[] $stmts
     * @return Node[]
>>>>>>> 2d84200b97 (introduce FileProcessResult, to align with PHPStan parallel architecture conventions and avoid using array shapes)
     */
    public function traverse(array $stmts): array
    {
        foreach ($this->postRectors as $postRector) {
            if ($this->shouldSkipPostRector($postRector)) {
                continue;
            }

            $this->currentRectorProvider->changeCurrentRector($postRector);

            $nodeTraverser = new NodeTraverser();
            $nodeTraverser->addVisitor($postRector);
            $stmts = $nodeTraverser->traverse($stmts);
        }

        return $stmts;
    }

    private function shouldSkipPostRector(PostRectorInterface $postRector): bool
    {
        $file = $this->currentFileProvider->getFile();
        if (! $file instanceof File) {
            return false;
        }

        $filePath = $file->getFilePath();
        if ($this->skipper->shouldSkipElementAndFilePath($postRector, $filePath)) {
            return true;
        }

        // skip renaming if rename class rector is skipped
        return $postRector instanceof ClassRenamingPostRector && $this->skipper->shouldSkipElementAndFilePath(
            RenameClassRector::class,
            $filePath
        );
    }
}
