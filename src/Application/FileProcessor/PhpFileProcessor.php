<?php

declare(strict_types=1);

namespace Rector\Core\Application\FileProcessor;

use PHPStan\AnalysedCodeException;
use Rector\ChangesReporting\ValueObjectFactory\ErrorFactory;
use Rector\Core\Application\FileDecorator\FileDiffFileDecorator;
use Rector\Core\Application\FileProcessor;
use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\Configuration\Configuration;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Contract\Processor\PhpFileProcessorInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\Printer\FormatPerservingPrinter;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Application\RectorError;
use Rector\PostRector\Application\PostFileProcessor;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;
use Throwable;

final class PhpFileProcessor implements FileProcessorInterface
{
    /**
     * Why 4? One for each cycle, so user sees some activity all the time:
     *
     * 1) parsing files
     * 2) main rectoring
     * 3) post-rectoring (removing files, importing names)
     * 4) printing
     *
     * @var int
     */
    private const PROGRESS_BAR_STEP_MULTIPLIER = 4;

    /**
     * @var File[]
     */
    private array $notParsedFiles = [];

    /**
     * @var PhpFileProcessorInterface[]
     */
    private array $phpFileProcessors;

    /**
     * @param PhpFileProcessorInterface[] $phpFileProcessors
     */
    public function __construct(
        private Configuration $configuration,
        private SymfonyStyle $symfonyStyle,
        private PrivatesAccessor $privatesAccessor,
        private CurrentFileProvider $currentFileProvider,
        private ErrorFactory $errorFactory,
        array $phpFileProcessors
    ) {
        $this->phpFileProcessors = $this->sortByPriority($phpFileProcessors);
    }

    /**
     * @param File[] $files
     */
    public function process(array $files): void
    {
        $fileCount = count($files);

        $this->prepareProgressBar($fileCount);

        foreach ($this->phpFileProcessors as $phpFileProcessor) {
            foreach ($files as $file) {
                $this->currentFileProvider->setFile($file);

                $this->advance($file, $phpFileProcessor->getPhase());
                if ($file->hasErrors()) {
                    $this->printFileErrors($file);
                    continue;
                }

                $this->tryCatchWrapper($file, $phpFileProcessor);
            }
        }

        if ($this->configuration->shouldShowProgressBar()) {
            $this->symfonyStyle->newLine(2);
        }
    }

    public function supports(File $file): bool
    {
        $smartFileInfo = $file->getSmartFileInfo();
        return $smartFileInfo->hasSuffixes($this->getSupportedFileExtensions());
    }

    /**
     * @return string[]
     */
    public function getSupportedFileExtensions(): array
    {
        return $this->configuration->getFileExtensions();
    }

    private function prepareProgressBar(int $fileCount): void
    {
        if ($this->symfonyStyle->isVerbose()) {
            return;
        }

        if (! $this->configuration->shouldShowProgressBar()) {
            return;
        }

        $this->configureStepCount($fileCount);
    }

    private function tryCatchWrapper(File $file, callable $callback): void
    {
        try {
            if (in_array($file, $this->notParsedFiles, true)) {
                // we cannot process this file
                return;
            }

            $callback($file);
        } catch (ShouldNotHappenException $shouldNotHappenException) {
            throw $shouldNotHappenException;
        } catch (AnalysedCodeException $analysedCodeException) {
            // inform about missing classes in tests
            if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
                throw $analysedCodeException;
            }

            $this->notParsedFiles[] = $file;
            $error = $this->errorFactory->createAutoloadError($analysedCodeException);
            $file->addRectorError($error);
        } catch (Throwable $throwable) {
            if ($this->symfonyStyle->isVerbose() || StaticPHPUnitEnvironment::isPHPUnitRun()) {
                throw $throwable;
            }

            $rectorError = new RectorError($throwable->getMessage(), $throwable->getLine());
            $file->addRectorError($rectorError);
        }
    }

    private function printFile(File $file): void
    {
        $smartFileInfo = $file->getSmartFileInfo();
        if ($this->removedAndAddedFilesCollector->isFileRemoved($smartFileInfo)) {
            // skip, because this file exists no more
            return;
        }

        $newContent = $this->configuration->isDryRun()
            ? $this->formatPerservingPrinter->printParsedStmstAndTokensToString($file)
            : $this->formatPerservingPrinter->printParsedStmstAndTokens($file);

        $file->changeFileContent($newContent);
        $this->fileDiffFileDecorator->decorate([$file]);
    }

    /**
     * This prevent CI report flood with 1 file = 1 line in progress bar
     */
    private function configureStepCount(int $fileCount): void
    {
        $this->symfonyStyle->progressStart($fileCount * self::PROGRESS_BAR_STEP_MULTIPLIER);

        $progressBar = $this->privatesAccessor->getPrivateProperty($this->symfonyStyle, 'progressBar');
        if (! $progressBar instanceof ProgressBar) {
            throw new ShouldNotHappenException();
        }

        if ($progressBar->getMaxSteps() < 40) {
            return;
        }

        $redrawFrequency = (int) ($progressBar->getMaxSteps() / 20);
        $progressBar->setRedrawFrequency($redrawFrequency);
    }

    private function advance(File $file, string $phase): void
    {
        if ($this->symfonyStyle->isVerbose()) {
            $smartFileInfo = $file->getSmartFileInfo();
            $relativeFilePath = $smartFileInfo->getRelativeFilePathFromDirectory(getcwd());
            $message = sprintf('[%s] %s', $phase, $relativeFilePath);
            $this->symfonyStyle->writeln($message);
        } elseif ($this->configuration->shouldShowProgressBar()) {
            $this->symfonyStyle->progressAdvance();
        }
    }

    /**
     * @param PhpFileProcessorInterface[] $phpFileProcessors
     *
     * @return PhpFileProcessorInterface[]
     */
    private function sortByPriority(array $phpFileProcessors): array
    {
        $phpFileProcessorsByPriority = [];

        foreach ($phpFileProcessors as $phpFileProcessor) {
            if (isset($phpFileProcessorsByPriority[$phpFileProcessor->getPriority()])) {
                throw new ShouldNotHappenException();
            }

            $phpFileProcessorsByPriority[$phpFileProcessor->getPriority()] = $phpFileProcessor;
        }

        krsort($phpFileProcessorsByPriority);

        return $phpFileProcessorsByPriority;
    }

    private function printFileErrors(File $file): void
    {
        if (! $this->symfonyStyle->isVerbose()) {
            return;
        }

        if (! $file->hasErrors()) {
            return;
        }

        foreach ($file->getErrors() as $rectorError) {
            $this->symfonyStyle->error($rectorError->getMessage());
        }
    }
}
