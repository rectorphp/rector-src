<?php

declare(strict_types=1);

namespace Rector\Parallel;

use Clue\React\NDJson\Decoder;
use Clue\React\NDJson\Encoder;
use Nette\Utils\FileSystem;
use PHPStan\Analyser\NodeScopeResolver;
use Rector\Core\Application\FileProcessor\PhpFileProcessor;
use Rector\Core\Console\Style\RectorConsoleOutputStyle;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\StaticReflection\DynamicSourceLocatorDecorator;
use Rector\Core\Util\ArrayParametersMerger;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Error\SystemError;
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

    public function __construct(
        private readonly ArrayParametersMerger $arrayParametersMerger,
        private readonly CurrentFileProvider $currentFileProvider,
        private readonly PhpFileProcessor $phpFileProcessor,
        private readonly NodeScopeResolver $nodeScopeResolver,
        private readonly DynamicSourceLocatorDecorator $dynamicSourceLocatorDecorator,
        private readonly RectorConsoleOutputStyle $rectorConsoleOutputStyle
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

            $errorAndFileDiffs = [];
            $systemErrors = [];

            // 1. allow PHPStan to work with static reflection on provided files
            $this->nodeScopeResolver->setAnalysedFiles($filePaths);

            foreach ($filePaths as $filePath) {
                try {
                    $file = new File($filePath, FileSystem::read($filePath));
                    $this->currentFileProvider->setFile($file);

                    if (! $this->phpFileProcessor->supports($file, $configuration)) {
                        continue;
                    }

                    $currentErrorsAndFileDiffs = $this->phpFileProcessor->process($file, $configuration);

                    $errorAndFileDiffs = $this->arrayParametersMerger->merge(
                        $errorAndFileDiffs,
                        $currentErrorsAndFileDiffs
                    );

                    // warn about deprecated @noRector annotation
                    if (! str_ends_with($file->getFilePath(), 'WorkerRunner.php')
                        && (
                            str_contains($file->getFileContent(), ' @noRector ') ||
                            str_contains($file->getFileContent(), ' @norector ')
                        )
                    ) {
                        $systemErrors[] = new SystemError(
                            'The @noRector annotation was deprecated and removed due to hiding fixed errors. Use more precise $rectorConfig->skip() method in the rector.php config.',
                            $file->getFilePath()
                        );
                        continue;
                    }
                } catch (Throwable $throwable) {
                    ++$systemErrorsCount;
                    $systemErrors = $this->collectSystemErrors($systemErrors, $throwable, $filePath);
                }
            }

            /**
             * this invokes all listeners listening $decoder->on(...) @see \Symplify\EasyParallel\Enum\ReactEvent::DATA
             */
            $encoder->write([
                ReactCommand::ACTION => Action::RESULT,
                self::RESULT => [
                    Bridge::FILE_DIFFS => $errorAndFileDiffs[Bridge::FILE_DIFFS] ?? [],
                    Bridge::FILES_COUNT => count($filePaths),
                    Bridge::SYSTEM_ERRORS => $systemErrors,
                    Bridge::SYSTEM_ERRORS_COUNT => $systemErrorsCount,
                ],
            ]);
        });

        $decoder->on(ReactEvent::ERROR, $handleErrorCallback);
    }

    /**
     * @param SystemError[] $systemErrors
     * @return SystemError[]
     */
    private function collectSystemErrors(array $systemErrors, Throwable $throwable, string $filePath): array
    {
        $errorMessage = sprintf('System error: "%s"', $throwable->getMessage()) . PHP_EOL;

        if ($this->rectorConsoleOutputStyle->isDebug()) {
            $systemErrors[] = new SystemError(
                $errorMessage . PHP_EOL . 'Stack trace:' . PHP_EOL . $throwable->getTraceAsString(),
                $filePath,
                $throwable->getLine()
            );
            return $systemErrors;
        }

        $errorMessage .= 'Run Rector with "--debug" option and post the report here: https://github.com/rectorphp/rector/issues/new';
        $systemErrors[] = new SystemError($errorMessage, $filePath, $throwable->getLine());

        return $systemErrors;
    }
}
