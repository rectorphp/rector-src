<?php

declare(strict_types=1);

namespace Rector\Parallel;

use Clue\React\NDJson\Decoder;
use Clue\React\NDJson\Encoder;
use Nette\Utils\FileSystem;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Core\Application\ApplicationFileProcessor;
use Rector\Core\Application\FileSystem\RemovedAndAddedFilesProcessor;
use Rector\Core\Console\Style\RectorConsoleOutputStyle;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Exception\ParsingException;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\StaticReflection\DynamicSourceLocatorDecorator;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\Parallel\ValueObject\Bridge;
use Symplify\EasyParallel\Enum\Action;
use Symplify\EasyParallel\Enum\ReactCommand;
use Symplify\EasyParallel\Enum\ReactEvent;
use Throwable;

final class WorkerRunner
{
    /**
     * @var string
     */
    private const RESULT = 'result';

    /**
     * @param FileProcessorInterface[] $fileProcessors
     */
    public function __construct(
        private readonly CurrentFileProvider $currentFileProvider,
        private readonly DynamicSourceLocatorDecorator $dynamicSourceLocatorDecorator,
        private readonly RectorConsoleOutputStyle $rectorConsoleOutputStyle,
        private readonly RemovedAndAddedFilesProcessor $removedAndAddedFilesProcessor,
        private readonly ApplicationFileProcessor $applicationFileProcessor,
        private readonly ChangedFilesDetector $changedFilesDetector,
        private readonly array $fileProcessors = [],
    ) {
    }

    public function run(Encoder $encoder, Decoder $decoder, Configuration $configuration): void
    {
        $this->dynamicSourceLocatorDecorator->addPaths($configuration->getPaths());

        // 1. handle system error
        $handleErrorCallback = static function (Throwable $throwable) use ($encoder): void {
            $systemErrors = new SystemError($throwable->getMessage(), $throwable->getFile(), $throwable->getLine());

            $encoder->write([
                ReactCommand::ACTION => Action::RESULT,
                self::RESULT => [
                    Bridge::SYSTEM_ERRORS => [$systemErrors],
                    Bridge::FILES_COUNT => 0,
                    Bridge::SYSTEM_ERRORS_COUNT => 1,
                ],
            ]);
            $encoder->end();
        };

        $encoder->on(ReactEvent::ERROR, $handleErrorCallback);

        // 2. collect diffs + errors from file processor
        $decoder->on(ReactEvent::DATA, function (array $json) use ($encoder, $configuration): void {
            $action = $json[ReactCommand::ACTION];
            if ($action !== Action::MAIN) {
                return;
            }

            $systemErrorsCount = 0;

            /** @var string[] $filePaths */
            $filePaths = $json[Bridge::FILES] ?? [];

            $fileDiffs = [];
            $systemErrors = [];

            // 1. allow PHPStan to work with static reflection on provided files
            $this->applicationFileProcessor->configurePHPStanNodeScopeResolver($filePaths, $configuration);

            foreach ($filePaths as $filePath) {
                $file = null;

                try {
                    $file = new File($filePath, FileSystem::read($filePath));
                    $this->currentFileProvider->setFile($file);

                    $fileDiff = $this->processFile($file, $configuration);

                    if ($fileDiff instanceof FileDiff) {
                        array_unshift($fileDiffs, $fileDiff);
                    }

                    if (! $configuration->isDryRun()) {
                        $this->changedFilesDetector->cacheFileWithDependencies($file->getFilePath());
                    }
                } catch (ParsingException $parsingException) {
                    ++$systemErrorsCount;
                    $systemErrors[] = $parsingException->getSystemError();

                    $this->invalidateFile($file);
                } catch (Throwable $throwable) {
                    ++$systemErrorsCount;
                    $systemErrors[] = $this->createSystemError($throwable, $filePath);

                    $this->invalidateFile($file);
                }
            }

            $this->removedAndAddedFilesProcessor->run($configuration);

            /**
             * this invokes all listeners listening $decoder->on(...) @see \Symplify\EasyParallel\Enum\ReactEvent::DATA
             */
            $encoder->write([
                ReactCommand::ACTION => Action::RESULT,
                self::RESULT => [
                    Bridge::FILE_DIFFS => $fileDiffs,
                    Bridge::FILES_COUNT => count($filePaths),
                    Bridge::SYSTEM_ERRORS => $systemErrors,
                    Bridge::SYSTEM_ERRORS_COUNT => $systemErrorsCount,
                ],
            ]);
        });

        $decoder->on(ReactEvent::ERROR, $handleErrorCallback);
    }

    private function processFile(File $file, Configuration $configuration): ?FileDiff
    {
        foreach ($this->fileProcessors as $fileProcessor) {
            if (! $fileProcessor->supports($file, $configuration)) {
                continue;
            }

            $fileDiff = $fileProcessor->process($file, $configuration);

            if ($fileDiff instanceof FileDiff) {
                return $fileDiff;
            }
        }

        return null;
    }

    private function createSystemError(Throwable $throwable, string $filePath): SystemError
    {
        $errorMessage = sprintf('System error: "%s"', $throwable->getMessage()) . PHP_EOL;

        if ($this->rectorConsoleOutputStyle->isDebug()) {
            return new SystemError(
                $errorMessage . PHP_EOL . 'Stack trace:' . PHP_EOL . $throwable->getTraceAsString(),
                $filePath,
                $throwable->getLine()
            );
        }

        $errorMessage .= 'Run Rector with "--debug" option and post the report here: https://github.com/rectorphp/rector/issues/new';
        return new SystemError($errorMessage, $filePath, $throwable->getLine());
    }

    private function invalidateFile(?File $file): void
    {
        if (!$file instanceof File) {
            return;
        }

        $this->changedFilesDetector->invalidateFile($file->getFilePath());
    }
}
