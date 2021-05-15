<?php
declare(strict_types=1);


namespace Rector\Core\Application\FileProcessor\PhpFileProcessor;

use Rector\Core\Application\FileProcessor;
use Rector\Core\Contract\Processor\PhpFileProcessorInterface;
use Rector\Core\ValueObject\Application\File;

final class ParseFileToNodeFileProcessor implements PhpFileProcessorInterface
{
    public function __construct(private FileProcessor $fileProcessor)
    {
    }

    public function __invoke(File $file): void
    {
        $this->fileProcessor->parseFileInfoToLocalCache($file);
    }

    public function getPhase(): string
    {
        return 'parsing';
    }

    public function getPriority(): int
    {
        return 1000;
    }
}
