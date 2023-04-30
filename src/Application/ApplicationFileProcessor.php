<?php

declare(strict_types=1);

namespace Rector\Core\Application;

use PHPStan\Analyser\NodeScopeResolver;
use Rector\Core\Application\FileDecorator\FileDiffFileDecorator;
use Rector\Core\Application\FileSystem\RemovedAndAddedFilesProcessor;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\ParameterProvider;
use Rector\Core\Contract\Console\OutputStyleInterface;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Util\ArrayParametersMerger;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\Core\ValueObjectFactory\Application\FileFactory;
use Rector\Parallel\Application\ParallelFileProcessor;
use Rector\Parallel\ValueObject\Bridge;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symplify\EasyParallel\CpuCoreCountProvider;
use Symplify\EasyParallel\Exception\ParallelShouldNotHappenException;
use Symplify\EasyParallel\ScheduleFactory;

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

    /**
     * @param FileProcessorInterface[] $fileProcessors
     */
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly FileDiffFileDecorator $fileDiffFileDecorator,
        private readonly RemovedAndAddedFilesProcessor $removedAndAddedFilesProcessor,
        private readonly OutputStyleInterface $rectorOutputStyle,
        private readonly FileFactory $fileFactory,
        private readonly NodeScopeResolver $nodeScopeResolver,
        private readonly ArrayParametersMerger $arrayParametersMerger,
        private readonly ParallelFileProcessor $parallelFileProcessor,
        private readonly ParameterProvider $parameterProvider,
        private readonly ScheduleFactory $scheduleFactory,
        private readonly CpuCoreCountProvider $cpuCoreCountProvider,
        private readonly array $fileProcessors = []
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
            // 1. collect all files from files+dirs provided paths
            $files = $this->fileFactory->createFromPaths($filePaths);

            // 2. PHPStan has to know about all files too
            $this->configurePHPStanNodeScopeResolver($filePaths, $configuration);

            $systemErrorsAndFileDiffs = $this->processFiles($files, $configuration);

            if ($configuration->shouldShowDiffs()) {
                $this->fileDiffFileDecorator->decorate($files);
            }

            $this->printFiles($files, $configuration);
        }

        $systemErrorsAndFileDiffs[Bridge::SYSTEM_ERRORS] = array_merge(
            $systemErrorsAndFileDiffs[Bridge::SYSTEM_ERRORS],
            $this->systemErrors
        );

        $this->restoreErrorHandler();

        return $systemErrorsAndFileDiffs;
    }

    /**
     * @api use only for tests
     *
     * @param File[] $files
     * @return array{system_errors: SystemError[], file_diffs: FileDiff[]}
     */
    public function processFiles(array $files, Configuration $configuration): array
    {
        $shouldShowProgressBar = $configuration->shouldShowProgressBar();
        if ($shouldShowProgressBar) {
            $fileCount = count($files);
            $this->rectorOutputStyle->progressStart($fileCount);
            $this->rectorOutputStyle->progressAdvance(0);
        }

        $systemErrorsAndFileDiffs = [
            Bridge::SYSTEM_ERRORS => [],
            Bridge::FILE_DIFFS => [],
        ];

        foreach ($files as $file) {
            foreach ($this->fileProcessors as $fileProcessor) {
                if (! $fileProcessor->supports($file, $configuration)) {
                    continue;
                }

                $result = $fileProcessor->process($file, $configuration);
                $systemErrorsAndFileDiffs = $this->arrayParametersMerger->merge($systemErrorsAndFileDiffs, $result);
            }

            // progress bar +1
            if ($shouldShowProgressBar) {
                $this->rectorOutputStyle->progressAdvance();
            }
        }

        $this->removedAndAddedFilesProcessor->run($configuration);

        return $systemErrorsAndFileDiffs;
    }

    /**
     * @param string[] $filePaths
     */
    public function configurePHPStanNodeScopeResolver(array $filePaths, Configuration $configuration): void
    {
        $fileExtensions = $configuration->getFileExtensions();
        $fileWithExtensionsFilter = static function (string $filePath) use ($fileExtensions): bool {
            $filePathExtension = pathinfo($filePath, PATHINFO_EXTENSION);
            return in_array($filePathExtension, $fileExtensions, true);
        };

        $filePaths = array_filter($filePaths, $fileWithExtensionsFilter);
        $this->nodeScopeResolver->setAnalysedFiles($filePaths);
    }

    /**
     * @param File[] $files
     */
    private function printFiles(array $files, Configuration $configuration): void
    {
        if ($configuration->isDryRun()) {
            return;
        }

        foreach ($files as $file) {
            if (! $file->hasChanged()) {
                continue;
            }

            $this->printFile($file);
        }
    }

    private function printFile(File $file): void
    {
        $filePath = $file->getFilePath();
        $this->filesystem->dumpFile($filePath, $file->getFileContent());

        // @todo how to keep original chmod rights?
        // $this->filesystem->chmod($filePath, $smartFileInfo->getPerms());
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
        // @todo possibly relative paths?
        // must be a string, otherwise the serialization returns empty arrays
        // $filePaths // = $this->filePathNormalizer->resolveFilePathsFromFileInfos($filePaths);

        $schedule = $this->scheduleFactory->create(
            $this->cpuCoreCountProvider->provide(),
            $this->parameterProvider->provideIntParameter(Option::PARALLEL_JOB_SIZE),
            $this->parameterProvider->provideIntParameter(Option::PARALLEL_MAX_NUMBER_OF_PROCESSES),
            $filePaths
        );

        $postFileCallback = static function (int $stepCount): void {
        };

        if ($configuration->shouldShowProgressBar()) {
            $fileCount = count($filePaths);
            $this->rectorOutputStyle->progressStart($fileCount);
            $this->rectorOutputStyle->progressAdvance(0);

            $postFileCallback = function (int $stepCount): void {
                $this->rectorOutputStyle->progressAdvance($stepCount);
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
