<?php
declare(strict_types=1);


namespace Rector\Core\Application\FileProcessor\PhpFileProcessor;

use Rector\Core\Contract\Processor\PhpFileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\PostRector\Application\PostFileProcessor;

final class ApplyPostRectorFileProcessor implements PhpFileProcessorInterface
{
    public function __construct(private PostFileProcessor $postFileProcessor)
    {
    }

    public function __invoke(File $file): void
    {
        $newStmts = $this->postFileProcessor->traverse($file->getNewStmts());

        // this is needed for new tokens added in "afterTraverse()"
        $file->changeNewStmts($newStmts);
    }

    public function getPhase(): string
    {
        return 'post rectors';
    }

    public function getPriority(): int
    {
        return 600;
    }
}
