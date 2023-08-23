<?php

declare(strict_types=1);

namespace Rector\Core\Application;

use Nette\Utils\FileSystem as UtilsFileSystem;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Core\Application\FileProcessor\PhpFileProcessor;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\Util\ArrayParametersMerger;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\Core\ValueObjectFactory\Application\FileFactory;
use Rector\Parallel\Application\ParallelFileProcessor;
use Rector\Parallel\ValueObject\Bridge;
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
        private readonly ArrayParametersMerger $arrayParametersMerger,
        private readonly ParallelFileProcessor $parallelFileProcessor,
        private readonly ScheduleFactory $scheduleFactory,
        private readonly CpuCoreCountProvider $cpuCoreCountProvider,
        private readonly ChangedFilesDetector $changedFilesDetector,
        private readonly CurrentFileProvider $currentFileProvider,
        private readonly PhpFileProcessor $phpFileProcessor,
    ) {
    }

    /**
     * @return array{system_errors: SystemError[], file_diffs: FileDiff[]}
     */
    public function run(Configuration $configuration, InputInterface $input): array
    {
        $filePaths = $this->fileFactory->findFilesInPaths($configuration->getPaths(), $configuration);

        // no files found
        if ($filePaths === []) {
            return [
                Bridge::SYSTEM_ERRORS => [],
                Bridge::FILE_DIFFS => [],
            ];
        }

        $this->configureCustomErrorHandler();

        if ($configuration->isParallel()) {
            $systemErrorsAndFileDiffs = $this->runParallel($filePaths, $configuration, $input);
        } else {
            $systemErrorsAndFileDiffs = $this->processFiles($filePaths, $configuration, false);
        }

        $systemErrorsAndFileDiffs[Bridge::SYSTEM_ERRORS] = array_merge(
            $systemErrorsAndFileDiffs[Bridge::SYSTEM_ERRORS],
            $this->systemErrors
        );

        $this->restoreErrorHandler();

        return $systemErrorsAndFileDiffs;
    }

    /**
     * @param string[] $filePaths
     * @return array{system_errors: SystemError[], file_diffs: FileDiff[], system_errors_count: int}
     */
    public function processFiles(array $filePaths, Configuration $configuration, bool $isParallel = true): array
    {
        $shouldShowProgressBar = $configuration->shouldShowProgressBar();

        // progress bar on parallel handled on runParallel()
        if (! $isParallel && $shouldShowProgressBar) {
            $fileCount = count($filePaths);
            $this->symfonyStyle->progressStart($fileCount);
            $this->symfonyStyle->progressAdvance(0);
        }

        $systemErrorsAndFileDiffs = [
            Bridge::SYSTEM_ERRORS => [],
            Bridge::FILE_DIFFS => [],
            Bridge::SYSTEM_ERRORS_COUNT => 0,
        ];

        foreach ($filePaths as $filePath) {
            $file = new File($filePath, UtilsFileSystem::read($filePath));

            try {
                $systemErrorsAndFileDiffs = $this->processFile($file, $systemErrorsAndFileDiffs, $configuration);

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

                $systemErrorsAndFileDiffs[Bridge::SYSTEM_ERRORS][] = $this->resolveSystemError($throwable, $filePath);
            }
        }

        return $systemErrorsAndFileDiffs;
    }

    /**
     * @param array{system_errors: SystemError[], file_diffs: FileDiff[], system_errors_count: int} $systemErrorsAndFileDiffs
     * @return array{system_errors: SystemError[], file_diffs: FileDiff[], system_errors_count: int}
     */
    private function processFile(File $file, array $systemErrorsAndFileDiffs, Configuration $configuration): array
    {
        $this->currentFileProvider->setFile($file);

        $phpSystemErrorsAndFileDiffs = $this->phpFileProcessor->process($file, $configuration);

        $systemErrorsAndFileDiffs = $this->arrayParametersMerger->merge(
            $systemErrorsAndFileDiffs,
            $phpSystemErrorsAndFileDiffs
        );

        if ($systemErrorsAndFileDiffs[Bridge::SYSTEM_ERRORS] !== []) {
            $this->changedFilesDetector->invalidateFile($file->getFilePath());
        } elseif (! $configuration->isDryRun() || $systemErrorsAndFileDiffs[Bridge::FILE_DIFFS] === []) {
            $this->changedFilesDetector->cacheFile($file->getFilePath());
        }

        return $systemErrorsAndFileDiffs;
    }

    private function resolveSystemError(Throwable $throwable, string|File $filePath): SystemError
    {
        $errorMessage = sprintf('System error: "%s"', $throwable->getMessage()) . PHP_EOL;
        $filePath = $filePath instanceof File
            ? $filePath->getFilePath()
            : $filePath;

        if ($this->symfonyStyle->isDebug()) {
            return new SystemError(
                $errorMessage . PHP_EOL . 'Stack trace:' . PHP_EOL . $throwable->getTraceAsString(),
                $filePath,
                $throwable->getLine()
            );
        }

        $errorMessage .= 'Run Rector with "--debug" option and post the report here: https://github.com/rectorphp/rector/issues/new';

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
     * @return array{system_errors: SystemError[], file_diffs: FileDiff[]}
     */
    private function runParallel(array $filePaths, Configuration $configuration, InputInterface $input): array
    {
        $schedule = $this->scheduleFactory->create(
            $this->cpuCoreCountProvider->provide(),
            SimpleParameterProvider::provideIntParameter(Option::PARALLEL_JOB_SIZE),
            SimpleParameterProvider::provideIntParameter(Option::PARALLEL_MAX_NUMBER_OF_PROCESSES),
            $filePaths
        );

        $postFileCallback = static function (int $stepCount): void {
        };

        if ($configuration->shouldShowProgressBar()) {
            $fileCount = count($filePaths);
            $this->symfonyStyle->progressStart($fileCount);
            $this->symfonyStyle->progressAdvance(0);

            $postFileCallback = function (int $stepCount): void {
                $this->symfonyStyle->progressAdvance($stepCount);
                // running in parallel here → nothing else to do
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
