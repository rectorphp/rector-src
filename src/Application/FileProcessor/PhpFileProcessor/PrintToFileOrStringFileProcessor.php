<?php
declare(strict_types=1);


namespace Rector\Core\Application\FileProcessor\PhpFileProcessor;

use Rector\Core\Application\FileDecorator\FileDiffFileDecorator;
use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\Configuration\Configuration;
use Rector\Core\Contract\Processor\PhpFileProcessorInterface;
use Rector\Core\PhpParser\Printer\FormatPerservingPrinter;
use Rector\Core\ValueObject\Application\File;

final class PrintToFileOrStringFileProcessor implements PhpFileProcessorInterface
{

    public function __construct(
        private Configuration $configuration,
        private FormatPerservingPrinter $formatPerservingPrinter,
        private FileDiffFileDecorator $fileDiffFileDecorator,
        private RemovedAndAddedFilesCollector $removedAndAddedFilesCollector
    )
    {
    }

    public function __invoke(File $file): void
    {
        $smartFileInfo = $file->getSmartFileInfo();
        if ($this->removedAndAddedFilesCollector->isFileRemoved($smartFileInfo)) {
            // skip, because this file exists no more
            return;
        }

        $newContent = $this->configuration->isDryRun() ? $this->formatPerservingPrinter->printParsedStmstAndTokensToString(
            $file
        ) : $this->formatPerservingPrinter->printParsedStmstAndTokens($file);

        $file->changeFileContent($newContent);
        $this->fileDiffFileDecorator->decorate([$file]);
    }

    public function getPhase(): string
    {
        return 'printing';
    }

    public function getPriority(): int
    {
        return 1;
    }
}
