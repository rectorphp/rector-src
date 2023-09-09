<?php

declare(strict_types=1);

namespace Rector\Core\Application;

use Nette\Utils\FileSystem as UtilsFileSystem;
use PHPStan\Collectors\CollectedData;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Core\Application\FileProcessor\PhpFileProcessor;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\Util\ArrayParametersMerger;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\FileProcessResult;
use Rector\Core\ValueObject\ProcessResult;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\Core\ValueObjectFactory\Application\FileFactory;
use Rector\Parallel\Application\ParallelFileProcessor;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
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
        private readonly FileFactory $fileFactory,
        private readonly ParallelFileProcessor $parallelFileProcessor,
        private readonly ScheduleFactory $scheduleFactory,
        private readonly CpuCoreCountProvider $cpuCoreCountProvider,
        private readonly ChangedFilesDetector $changedFilesDetector,
        private readonly CurrentFileProvider $currentFileProvider,
        private readonly PhpFileProcessor $phpFileProcessor,
        private readonly ArrayParametersMerger $arrayParametersMerger,
    ) {
    }

    public function run(Configuration $configuration, InputInterface $input): ProcessResult
    {
        $filePaths = $this->fileFactory->findFilesInPaths($configuration->getPaths(), $configuration);

        // no files found
        if ($filePaths === []) {
            return new ProcessResult([], [], []);
        }

        $this->configureCustomErrorHandler();

        if ($configuration->isParallel()) {
            $processResult = $this->runParallel($filePaths, $configuration, $input);
        } else {
            $processResult = $this->processFiles($filePaths, $configuration, false);
        }

        $processResult->addSystemErrors($this->systemErrors);

        $this->restoreErrorHandler();

        return $processResult;
    }

    /**
     * @param string[] $filePaths
     */
    public function processFiles(array $filePaths, Configuration $configuration, bool $isParallel = true): ProcessResult
    {
        $shouldShowProgressBar = $configuration->shouldShowProgressBar();

        // progress bar on parallel handled on runParallel()
        if (! $isParallel && $shouldShowProgressBar) {
            $fileCount = count($filePaths);
            $this->symfonyStyle->progressStart($fileCount);
            $this->symfonyStyle->progressAdvance(0);
        }

        /** @var SystemError[] $systemErrors */
        $systemErrors = [];

        /** @var FileDiff[] $fileDiffs */
        $fileDiffs = [];

        /** @var CollectedData[] $collectedData */
        $collectedData = [];

        foreach ($filePaths as $filePath) {
            $file = new File($filePath, UtilsFileSystem::read($filePath));

            try {
                $fileProcessResult = $this->processFile($file, $configuration);

                $systemErrors = $this->arrayParametersMerger->merge(
                    $systemErrors,
                    $fileProcessResult->getSystemErrors()
                );

                $currentFileDiff = $fileProcessResult->getFileDiff();
                if ($currentFileDiff instanceof FileDiff) {
                    $fileDiffs[] = $currentFileDiff;
                }

                $collectedData = array_merge($collectedData, $fileProcessResult->getCollectedData());

                // progress bar +1,
                // progress bar on parallel handled on runParallel()
                if (! $isParallel && $shouldShowProgressBar) {
                    $this->symfonyStyle->progressAdvance();
                }
            } catch (Throwable $throwable) {
                $this->changedFilesDetector->invalidateFile($filePath);

                if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
                    throw $throwable;
                }

                $systemErrors[] = $this->resolveSystemError($throwable, $filePath);
            }
        }

        return new ProcessResult($systemErrors, $fileDiffs, $collectedData);
    }

    private function processFile(File $file, Configuration $configuration): FileProcessResult
    {
        $this->currentFileProvider->setFile($file);

        $fileProcessResult = $this->phpFileProcessor->process($file, $configuration);

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
     */
    private function runParallel(array $filePaths, Configuration $configuration, InputInterface $input): ProcessResult
    {
        $schedule = $this->scheduleFactory->create(
            $this->cpuCoreCountProvider->provide(),
            SimpleParameterProvider::provideIntParameter(Option::PARALLEL_JOB_SIZE),
            SimpleParameterProvider::provideIntParameter(Option::PARALLEL_MAX_NUMBER_OF_PROCESSES),
            $filePaths
        );

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

        $potentialEcsBinaryPath = $_SERVER[self::ARGV][0];
        if (! file_exists($potentialEcsBinaryPath)) {
            return null;
        }

        return $potentialEcsBinaryPath;
    }
}
