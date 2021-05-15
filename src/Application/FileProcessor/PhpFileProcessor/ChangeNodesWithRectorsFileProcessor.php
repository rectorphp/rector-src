<?php
declare(strict_types=1);


namespace Rector\Core\Application\FileProcessor\PhpFileProcessor;

use Rector\Core\Application\FileProcessor;
use Rector\Core\Contract\Processor\PhpFileProcessorInterface;
use Rector\Core\ValueObject\Application\File;

final class ChangeNodesWithRectorsFileProcessor implements PhpFileProcessorInterface
{
    public function __construct(private FileProcessor $fileProcessor)
    {
    }

    public function __invoke(File $file): void
    {
        $this->fileProcessor->refactor($file);
    }

    public function getPhase(): string
    {
        return 'refactoring';
    }

    public function getPriority(): int
    {
        return 800;
    }
}
