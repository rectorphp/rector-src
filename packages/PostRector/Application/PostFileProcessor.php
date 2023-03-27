<?php

declare(strict_types=1);

namespace Rector\PostRector\Application;

use PhpParser\Node\Stmt;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Logging\CurrentRectorProvider;
use Rector\Core\PhpParser\NodeTraverser\CleanVisitorNodeTraverser;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\PostRector\Contract\Rector\PostRectorDependencyInterface;
use Rector\PostRector\Contract\Rector\PostRectorInterface;
use Rector\Skipper\Skipper\Skipper;

final class PostFileProcessor
{
    /**
     * @var PostRectorInterface[]
     */
    private array $postRectors = [];

    /**
     * @param PostRectorInterface[] $postRectors
     */
    public function __construct(
        private readonly Skipper $skipper,
        private readonly CurrentFileProvider $currentFileProvider,
        private readonly CurrentRectorProvider $currentRectorProvider,
        private readonly CleanVisitorNodeTraverser $cleanVisitorNodeTraverser,
        array $postRectors
    ) {
        $this->postRectors = $this->sortByPriority($postRectors);
    }

    /**
     * @param Stmt[] $stmts
     * @return Stmt[]
     */
    public function traverse(array $stmts): array
    {
        foreach ($this->postRectors as $postRector) {
            if ($this->shouldSkipPostRector($postRector)) {
                continue;
            }

            $this->currentRectorProvider->changeCurrentRector($postRector);

            $this->cleanVisitorNodeTraverser->addVisitor($postRector);
            $stmts = $this->cleanVisitorNodeTraverser->traverse($stmts);
        }

        return $stmts;
    }

    /**
     * @param PostRectorInterface[] $postRectors
     * @return PostRectorInterface[]
     */
    private function sortByPriority(array $postRectors): array
    {
        $postRectorsByPriority = [];

        foreach ($postRectors as $postRector) {
            if (isset($postRectorsByPriority[$postRector->getPriority()])) {
                $errorMessage = sprintf(
                    'There are multiple post rectors with the same priority: %d. Use different one for your new PostRector',
                    $postRector->getPriority()
                );

                throw new ShouldNotHappenException($errorMessage);
            }

            $postRectorsByPriority[$postRector->getPriority()] = $postRector;
        }

        krsort($postRectorsByPriority);

        return $postRectorsByPriority;
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

        if ($postRector instanceof PostRectorDependencyInterface) {
            $dependencies = $postRector->getRectorDependencies();
            foreach ($dependencies as $dependency) {
                if ($this->skipper->shouldSkipElementAndFilePath($dependency, $filePath)) {
                    return true;
                }
            }
        }

        return false;
    }
}
