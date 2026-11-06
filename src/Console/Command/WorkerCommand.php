<?php

declare(strict_types=1);

namespace Rector\Console\Command;

use Clue\React\NDJson\Decoder;
use Clue\React\NDJson\Encoder;
use Entropy\Console\Contract\CommandInterface;
use Entropy\Console\Contract\HiddenCommandInterface;
use React\EventLoop\StreamSelectLoop;
use React\Socket\ConnectionInterface;
use React\Socket\TcpConnector;
use Rector\Application\ApplicationFileProcessor;
use Rector\Autoloading\AdditionalAutoloader;
use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\ChangesReporting\Output\JsonOutputFormatter;
use Rector\Configuration\ConfigurationFactory;
use Rector\Configuration\ConfigurationRuleFilter;
use Rector\Console\ExitCode;
use Rector\Parallel\Enum\Action;
use Rector\Parallel\Enum\ReactCommand;
use Rector\Parallel\Enum\ReactEvent;
use Rector\Parallel\Enum\StreamFormat;
use Rector\Parallel\ValueObject\Bridge;
use Rector\StaticReflection\DynamicSourceLocatorDecorator;
use Rector\Util\MemoryLimiter;
use Rector\ValueObject\Configuration;
use Rector\ValueObject\Error\SystemError;
use Throwable;
use Webmozart\Assert\Assert;

/**
 * Inspired at: https://github.com/phpstan/phpstan-src/commit/9124c66dcc55a222e21b1717ba5f60771f7dda92
 * https://github.com/phpstan/phpstan-src/blob/c471c7b050e0929daf432288770de673b394a983/src/Command/WorkerCommand.php
 *
 * ↓↓↓
 * https://github.com/phpstan/phpstan-src/commit/b84acd2e3eadf66189a64fdbc6dd18ff76323f67#diff-7f625777f1ce5384046df08abffd6c911cfbb1cfc8fcb2bdeaf78f337689e3e2
 */
final readonly class WorkerCommand implements CommandInterface, HiddenCommandInterface
{
    private const string RESULT = 'result';

    public function __construct(
        private AdditionalAutoloader $additionalAutoloader,
        private DynamicSourceLocatorDecorator $dynamicSourceLocatorDecorator,
        private ApplicationFileProcessor $applicationFileProcessor,
        private MemoryLimiter $memoryLimiter,
        private ConfigurationFactory $configurationFactory,
        private ConfigurationRuleFilter $configurationRuleFilter,
    ) {
    }

    public function getName(): string
    {
        return 'worker';
    }

    public function getDescription(): string
    {
        return '[INTERNAL] Support for parallel process';
    }

    /**
     * @param string[] $only           Fully qualified rule class name; repeat to run several rules
     * @param string   $config         Path to config file
     * @param string   $autoloadFile   Path to file with extra autoload (will be included)
     * @param string   $outputFormat   Select output format
     * @param string   $onlySuffix     Deprecated, use "--filter" instead
     * @param string   $filter         Keep only files matching all comma-separated patterns
     * @param string   $memoryLimit    Memory limit for process
     * @param string   $maxChanges     Stop after this many changes are made
     * @param string   $port           [INTERNAL] parallel TCP port
     * @param string   $identifier     [INTERNAL] parallel identifier
     * @param string ...$paths         Files or directories to be upgraded
     *
     * @option $only
     * @option $config
     * @option $autoloadFile
     * @option $outputFormat
     * @option $onlySuffix
     * @option $filter
     * @option $memoryLimit
     * @option $maxChanges
     * @option $port
     * @option $identifier
     *
     * @api invoked via reflection by the Entropy console application
     *
     * @return ExitCode::*
     */
    public function run(
        bool $dryRun = false,
        bool $clearCache = false,
        bool $noProgressBar = false,
        bool $noDiffs = false,
        bool $debug = false,
        bool $composerBased = false,
        bool $php = false,
        bool $rulesSummary = false,
        array $only = [],
        string $config = '',
        string $autoloadFile = '',
        string $outputFormat = ConsoleOutputFormatter::NAME,
        string $onlySuffix = '',
        string $filter = '',
        string $memoryLimit = '',
        string $maxChanges = '',
        string $port = '',
        string $identifier = '',
        string ...$paths,
    ): int {
        $configuration = $this->configurationFactory->create(
            array_values($paths),
            $dryRun,
            $clearCache,
            $noProgressBar,
            $noDiffs,
            $outputFormat,
            $only,
            $composerBased,
            $php,
            $onlySuffix !== '' ? $onlySuffix : null,
            $filter !== '' ? $filter : null,
            $debug,
            $memoryLimit !== '' ? $memoryLimit : null,
            $port,
            $identifier,
            $maxChanges !== '' ? (int) $maxChanges : null,
            $rulesSummary,
        );

        $this->memoryLimiter->adjust($configuration);
        $this->configurationRuleFilter->setConfiguration($configuration);

        $streamSelectLoop = new StreamSelectLoop();
        $parallelIdentifier = $configuration->getParallelIdentifier();

        $tcpConnector = new TcpConnector($streamSelectLoop);

        $promise = $tcpConnector->connect('127.0.0.1:' . $configuration->getParallelPort());
        $promise->then(function (ConnectionInterface $connection) use (
            $parallelIdentifier,
            $configuration
        ): void {
            $inDecoder = new Decoder($connection, true, StreamFormat::DEPTH, JSON_INVALID_UTF8_IGNORE, StreamFormat::MAX_LENGTH);
            $outEncoder = new Encoder($connection, JSON_INVALID_UTF8_IGNORE);

            $outEncoder->write([
                ReactCommand::ACTION => Action::HELLO,
                ReactCommand::IDENTIFIER => $parallelIdentifier,
            ]);

            $this->runWorker($outEncoder, $inDecoder, $configuration);
        });

        $streamSelectLoop->run();

        return ExitCode::SUCCESS;
    }

    private function runWorker(
        Encoder $encoder,
        Decoder $decoder,
        Configuration $configuration
    ): void {
        $this->additionalAutoloader->autoloadFile();
        $this->additionalAutoloader->autoloadPaths();

        $this->dynamicSourceLocatorDecorator->addPaths($configuration->getPaths());

        if ($configuration->isDebug()) {
            $preFileCallback = static function (string $filePath): void {
                echo $filePath . PHP_EOL;
            };
        } else {
            $preFileCallback = null;
        }

        // 1. handle system error
        $handleErrorCallback = static function (Throwable $throwable) use ($encoder): void {
            $systemError = new SystemError($throwable->getMessage(), $throwable->getFile(), $throwable->getLine());

            $encoder->write([
                ReactCommand::ACTION => Action::RESULT,
                self::RESULT => [
                    Bridge::SYSTEM_ERRORS => [$systemError],
                    Bridge::FILES_COUNT => 0,
                    Bridge::SYSTEM_ERRORS_COUNT => 1,
                ],
            ]);
            $encoder->end();
        };

        $encoder->on(ReactEvent::ERROR, $handleErrorCallback);

        // 2. collect diffs + errors from file processor
        $decoder->on(ReactEvent::DATA, function (array $json) use (
            $preFileCallback,
            $encoder,
            $configuration
        ): void {
            $action = $json[ReactCommand::ACTION];
            if ($action !== Action::MAIN) {
                return;
            }

            /** @var string[] $filePaths */
            $filePaths = $json[Bridge::FILES] ?? [];

            Assert::notEmpty($filePaths);

            $processResult = $this->applicationFileProcessor->processFiles(
                $filePaths,
                $configuration,
                $preFileCallback
            );

            /**
             * this invokes all listeners listening $decoder->on(...) @see \Rector\Parallel\Enum\ReactEvent::DATA
             */
            $encoder->write([
                ReactCommand::ACTION => Action::RESULT,
                self::RESULT => [
                    Bridge::FILE_DIFFS => $processResult->getFileDiffs(
                        $configuration->getOutputFormat() !== JsonOutputFormatter::NAME
                    ),
                    Bridge::FILES_COUNT => count($filePaths),
                    Bridge::SYSTEM_ERRORS => $processResult->getSystemErrors(),
                    Bridge::SYSTEM_ERRORS_COUNT => count($processResult->getSystemErrors()),
                    Bridge::TOTAL_CHANGED => $processResult->getTotalChanged(),
                    Bridge::USED_SKIPS => $processResult->getUsedSkips(),
                ],
            ]);
        });

        $decoder->on(ReactEvent::ERROR, $handleErrorCallback);
    }
}
