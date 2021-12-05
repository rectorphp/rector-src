<?php

declare(strict_types=1);

namespace Rector\Core\Application\FileProcessor;

use PHPStan\AnalysedCodeException;
use Rector\ChangesReporting\ValueObjectFactory\ErrorFactory;
use Rector\Core\Application\FileDecorator\FileDiffFileDecorator;
use Rector\Core\Application\FileProcessor;
use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Enum\ApplicationPhase;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\Printer\FormatPerservingPrinter;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Application\SystemError;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\Parallel\ValueObject\Bridge;
use Rector\PostRector\Application\PostFileProcessor;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class PhpFileProcessor implements FileProcessorInterface
{
    /**
     * @var File[]
     */
    private array $notParsedFiles = [];

    public function __construct(
        private readonly FormatPerservingPrinter $formatPerservingPrinter,
        private readonly FileProcessor $fileProcessor,
        private readonly RemovedAndAddedFilesCollector $removedAndAddedFilesCollector,
        private readonly SymfonyStyle $symfonyStyle,
        private readonly FileDiffFileDecorator $fileDiffFileDecorator,
        private readonly CurrentFileProvider $currentFileProvider,
        private readonly PostFileProcessor $postFileProcessor,
        private readonly ErrorFactory $errorFactory
    ) {
    }

    /**
     * @return array{system_errors: SystemError[], file_diffs: FileDiff[]}
     */
    public function process(File $file, Configuration $configuration): array
    {
        $systemErrorsAndFileDiffs = [
            Bridge::SYSTEM_ERRORS => [],
            Bridge::FILE_DIFFS => [],
        ];

        // 1. parse files to nodes
        $parsingSystemErrors = $this->parseFileAndDecorateNodes($file);
        if ($parsingSystemErrors !== []) {
            // we cannot process this file as the parsing and type resolving itself went wrong
            $systemErrorsAndFileDiffs[Bridge::SYSTEM_ERRORS] = $parsingSystemErrors;
            return $systemErrorsAndFileDiffs;
        }

        // 2. change nodes with Rectors
        $loopCounter = 0;
        do {
            ++$loopCounter;

            if ($loopCounter === 10) { // ensure no infinite loop
                break;
            }

            $file->changeHasChanged(false);
            $this->refactorNodesWithRectors($file, $configuration);

            // 3. apply post rectors
            $newStmts = $this->postFileProcessor->traverse($file->getNewStmts());
            // this is needed for new tokens added in "afterTraverse()"
            $file->changeNewStmts($newStmts);
            $this->notifyPhase($file, ApplicationPhase::POST_RECTORS());

            // 4. print to file or string
            $this->currentFileProvider->setFile($file);

            // cannot print file with errors, as print would break everything to original nodes
            if ($file->hasErrors()) {
                // cannot print file with errors, as print would b
                $this->notifyPhase($file, ApplicationPhase::PRINT_SKIP());
                continue;
            }

            // important to detect if file has changed
            $this->printFile($file, $configuration);
            $this->notifyPhase($file, ApplicationPhase::PRINT());
        } while ($file->hasChanged());

        // return json here
        $fileDiff = $file->getFileDiff();

        return [
            Bridge::SYSTEM_ERRORS => $file->getErrors(),
            Bridge::FILE_DIFFS => $fileDiff instanceof FileDiff ? [$fileDiff] : [],
        ];
    }

    public function supports(File $file, Configuration $configuration): bool
    {
        $smartFileInfo = $file->getSmartFileInfo();
        return $smartFileInfo->hasSuffixes($configuration->getFileExtensions());
    }

    /**
     * @return string[]
     */
    public function getSupportedFileExtensions(): array
    {
        return ['php'];
    }

    private function refactorNodesWithRectors(File $file, Configuration $configuration): void
    {
        $this->currentFileProvider->setFile($file);

        $this->fileProcessor->refactor($file, $configuration);
        $this->notifyPhase($file, ApplicationPhase::REFACTORING());
    }


    private function parseFileAndDecorateNodes(File $file): array
    {
        $this->currentFileProvider->setFile($file);
        $this->notifyPhase($file, ApplicationPhase::PARSING());

        try {
            $this->fileProcessor->parseFileInfoToLocalCache($file);
        } catch (ShouldNotHappenException $shouldNotHappenException) {
            throw $shouldNotHappenException;
        } catch (AnalysedCodeException $analysedCodeException) {
            // inform about missing classes in tests
            if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
                throw $analysedCodeException;
            }

            $this->notParsedFiles[] = $file;
            $autoloadSystemError = $this->errorFactory->createAutoloadError(
                $analysedCodeException,
                $file->getSmartFileInfo()
            );
            return [$autoloadSystemError];
        } catch (Throwable $throwable) {
            if ($this->symfonyStyle->isVerbose() || StaticPHPUnitEnvironment::isPHPUnitRun()) {
                throw $throwable;
            }

            $systemError = new SystemError(
                $throwable->getMessage(),
                $file->getRelativeFilePath(),
                $throwable->getLine()
            );

            return [$systemError];
        }

        return [];
    }

    private function printFile(File $file, Configuration $configuration): void
    {
        $smartFileInfo = $file->getSmartFileInfo();
        if ($this->removedAndAddedFilesCollector->isFileRemoved($smartFileInfo)) {
            // skip, because this file exists no more
            return;
        }

        $newContent = $configuration->isDryRun()
            ? $this->formatPerservingPrinter->printParsedStmstAndTokensToString($file)
            : $this->formatPerservingPrinter->printParsedStmstAndTokens($file);

        $file->changeFileContent($newContent);
        $this->fileDiffFileDecorator->decorate([$file]);
    }

    private function notifyPhase(File $file, ApplicationPhase $applicationPhase): void
    {
        if (! $this->symfonyStyle->isVerbose()) {
            return;
        }

        $smartFileInfo = $file->getSmartFileInfo();
        $relativeFilePath = $smartFileInfo->getRelativeFilePathFromDirectory(getcwd());
        $message = sprintf('[%s] %s', $applicationPhase, $relativeFilePath);
        $this->symfonyStyle->writeln($message);
    }
}
