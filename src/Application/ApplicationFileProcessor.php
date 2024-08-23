<?php

declare(strict_types=1);

namespace Rector\Application;

use Nette\Utils\FileSystem as UtilsFileSystem;
use PHPStan\Parser\ParserErrorsException;
use Rector\Application\Provider\CurrentFileProvider;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Caching\FileDependenciesCache;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\FileSystem\FilesFinder;
use Rector\Parallel\Application\ParallelFileProcessor;
use Rector\PhpParser\Parser\ParserErrors;
use Rector\Reporting\MissConfigurationReporter;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Rector\Util\ArrayParametersMerger;
use Rector\ValueObject\Application\File;
use Rector\ValueObject\Configuration;
use Rector\ValueObject\Error\SystemError;
use Rector\ValueObject\FileProcessResult;
use Rector\ValueObject\ProcessResult;
use Rector\ValueObject\Reporting\FileDiff;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\EasyParallel\CpuCoreCountProvider;
use Symplify\EasyParallel\Exception\ParallelShouldNotHappenException;
use Symplify\EasyParallel\ScheduleFactory;
use Throwable;

final class ApplicationFileProcessor
{
    /**
     * @var string
     */
    private const ARGV = 'argv';

    /**
     * @var SystemError[]
     */
    private array $systemErrors = [];

    public function __construct(
        private readonly SymfonyStyle $symfonyStyle,
        private readonly FilesFinder $filesFinder,
        private readonly ParallelFileProcessor $parallelFileProcessor,
        private readonly ScheduleFactory $scheduleFactory,
        private readonly CpuCoreCountProvider $cpuCoreCountProvider,
        private readonly ChangedFilesDetector $changedFilesDetector,
        private readonly CurrentFileProvider $currentFileProvider,
        private readonly FileProcessor $fileProcessor,
        private readonly ArrayParametersMerger $arrayParametersMerger,
        private readonly MissConfigurationReporter $missConfigurationReporter,
        private readonly FileDependenciesCache $fileDependenciesCache,
    ) {
    }

    public function run(Configuration $configuration, InputInterface $input): ProcessResult
    {
        $allFiles = $this->filesFinder->findFilesInPaths($configuration->getPaths(), $configuration);
        $filePaths = $this->filesFinder->filterUnchangedFiles($allFiles);

        $filePaths = [...$filePaths, ...$this->findDependentFiles($allFiles, $filePaths)];

        $this->missConfigurationReporter->reportVendorInPaths($filePaths);
        $this->missConfigurationReporter->reportStartWithShortOpenTag();

        // no files found
        if ($filePaths === []) {
            return new ProcessResult([], []);
        }

        $this->configureCustomErrorHandler();

        /**
         * Mimic @see https://github.com/phpstan/phpstan-src/blob/ab154e1da54d42fec751e17a1199b3e07591e85e/src/Command/AnalyseApplication.php#L188C23-L244
         */
        if ($configuration->shouldShowProgressBar()) {
            $fileCount = count($filePaths);
            $this->symfonyStyle->progressStart($fileCount);
            $this->symfonyStyle->progressAdvance(0);

            $postFileCallback = function (int $stepCount): void {
                $this->symfonyStyle->progressAdvance($stepCount);
                // running in parallel here → nothing else to do
            };
        } else {
            $postFileCallback = static function (int $stepCount): void {
            };
        }

        if ($configuration->isDebug()) {
            $preFileCallback = function (string $filePath): void {
                $this->symfonyStyle->writeln('[file] ' . $filePath);
            };
        } else {
            $preFileCallback = null;
        }

        if ($configuration->isParallel()) {
            $this->fileDependenciesCache->cacheAllFiles($allFiles);
            $processResult = $this->runParallel($filePaths, $input, $postFileCallback);
        } else {
            $processResult = $this->processFiles(
                $filePaths,
                $configuration,
                $allFiles,
                $preFileCallback,
                $postFileCallback
            );
        }

        $processResult->addSystemErrors($this->systemErrors);

        $this->restoreErrorHandler();

        return $processResult;
    }

    /**
     * @param string[] $filePaths
     * @param string[] $allFiles
     * @param callable(string $file): void|null $preFileCallback
     * @param callable(int $fileCount): void|null $postFileCallback
     */
    public function processFiles(
        array $filePaths,
        Configuration $configuration,
        ?array $allFiles = null,
        ?callable $preFileCallback = null,
        ?callable $postFileCallback = null,
    ): ProcessResult {
        /** @var SystemError[] $systemErrors */
        $systemErrors = [];

        /** @var FileDiff[] $fileDiffs */
        $fileDiffs = [];

        if ($allFiles === null) {
            $allFiles = $this->fileDependenciesCache->getAllFiles();
        }

        $allFiles = array_fill_keys($allFiles, true);

        foreach ($filePaths as $filePath) {
            if ($preFileCallback !== null) {
                $preFileCallback($filePath);
            }

            $file = new File($filePath, UtilsFileSystem::read($filePath));

            try {
                $fileProcessResult = $this->processFile($file, $allFiles, $configuration);

                $systemErrors = $this->arrayParametersMerger->merge(
                    $systemErrors,
                    $fileProcessResult->getSystemErrors()
                );

                $currentFileDiff = $fileProcessResult->getFileDiff();
                if ($currentFileDiff instanceof FileDiff) {
                    $fileDiffs[] = $currentFileDiff;
                }

                // progress bar on parallel handled on runParallel()
                if (is_callable($postFileCallback)) {
                    $postFileCallback(1);
                }
            } catch (Throwable $throwable) {
                $this->changedFilesDetector->invalidateFile($filePath);

                if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
                    throw $throwable;
                }

                $systemErrors[] = $this->resolveSystemError($throwable, $filePath);
            }
        }

        return new ProcessResult($systemErrors, $fileDiffs);
    }

    /**
     * @param string[] $possibleDependentFiles
     * @param string[] $filePaths
     * @return string[]
     */
    private function findDependentFiles(array $possibleDependentFiles, array $filePaths): array
    {
        $filesToAnalise = array_flip($filePaths);

        $dependentFiles = [];
        foreach ($possibleDependentFiles as $possibleDependentFile) {
            if (! isset($filesToAnalise[$possibleDependentFile])) {
                $fileDependencies = $this->fileDependenciesCache->getFileDependencies($possibleDependentFile);

                if ($fileDependencies !== null) {
                    foreach ($fileDependencies as $fileDependency) {
                        if (isset($filesToAnalise[$fileDependency])) {
                            $dependentFiles[] = $possibleDependentFile;
                            $this->changedFilesDetector->invalidateFile($possibleDependentFile);
                            break;
                        }
                    }
                }
            }
        }

        return $dependentFiles;
    }

    /**
     * @param array<string,true> $allFiles
     */
    private function processFile(File $file, array $allFiles, Configuration $configuration): FileProcessResult
    {
        $this->currentFileProvider->setFile($file);

        $fileProcessResult = $this->fileProcessor->processFile($file, $allFiles, $configuration);

        if ($fileProcessResult->getSystemErrors() !== []) {
            $this->changedFilesDetector->invalidateFile($file->getFilePath());
        } elseif (! $configuration->isDryRun() || ! $fileProcessResult->getFileDiff() instanceof FileDiff) {
            $this->changedFilesDetector->cacheFile($file->getFilePath());
        }

        return $fileProcessResult;
    }

    private function resolveSystemError(Throwable $throwable, string $filePath): SystemError
    {
        $errorMessage = sprintf('System error: "%s"', $throwable->getMessage()) . PHP_EOL;

        if ($this->symfonyStyle->isDebug()) {
            $errorMessage .= PHP_EOL . 'Stack trace:' . PHP_EOL . $throwable->getTraceAsString();
        } else {
            $errorMessage .= 'Run Rector with "--debug" option and post the report here: https://github.com/rectorphp/rector/issues/new';
        }

        if ($throwable instanceof ParserErrorsException) {
            $throwable = new ParserErrors($throwable);
        }

        return new SystemError($errorMessage, $filePath, $throwable->getLine());
    }

    /**
     * Inspired by @see https://github.com/phpstan/phpstan-src/blob/89af4e7db257750cdee5d4259ad312941b6b25e8/src/Analyser/Analyser.php#L134
     */
    private function configureCustomErrorHandler(): void
    {
        $errorHandlerCallback = function (int $code, string $message, string $file, int $line): bool {
            if ((error_reporting() & $code) === 0) {
                // silence @ operator
                return true;
            }

            // not relevant for us
            if (in_array($code, [E_DEPRECATED, E_WARNING], true)) {
                return true;
            }

            $this->systemErrors[] = new SystemError($message, $file, $line);

            return true;
        };

        set_error_handler($errorHandlerCallback);
    }

    private function restoreErrorHandler(): void
    {
        restore_error_handler();
    }

    /**
     * @param string[] $filePaths
     * @param callable(int $stepCount): void $postFileCallback
     */
    private function runParallel(
        array $filePaths,
        InputInterface $input,
        callable $postFileCallback
    ): ProcessResult {
        $schedule = $this->scheduleFactory->create(
            $this->cpuCoreCountProvider->provide(),
            SimpleParameterProvider::provideIntParameter(Option::PARALLEL_JOB_SIZE),
            SimpleParameterProvider::provideIntParameter(Option::PARALLEL_MAX_NUMBER_OF_PROCESSES),
            $filePaths
        );

        $mainScript = $this->resolveCalledRectorBinary();
        if ($mainScript === null) {
            throw new ParallelShouldNotHappenException('[parallel] Main script was not found');
        }

        // mimics see https://github.com/phpstan/phpstan-src/commit/9124c66dcc55a222e21b1717ba5f60771f7dda92#diff-387b8f04e0db7a06678eb52ce0c0d0aff73e0d7d8fc5df834d0a5fbec198e5daR139
        return $this->parallelFileProcessor->process($schedule, $mainScript, $postFileCallback, $input);
    }

    /**
     * Path to called "rector" binary file, e.g. "vendor/bin/rector" returns "vendor/bin/rector" This is needed to re-call the
     * ecs binary in sub-process in the same location.
     */
    private function resolveCalledRectorBinary(): ?string
    {
        if (! isset($_SERVER[self::ARGV][0])) {
            return null;
        }

        $potentialRectorBinaryPath = $_SERVER[self::ARGV][0];
        if (! file_exists($potentialRectorBinaryPath)) {
            return null;
        }

        return $potentialRectorBinaryPath;
    }
}
