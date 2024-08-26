<?php

declare(strict_types=1);

namespace Rector\Console\Command;

use Clue\React\NDJson\Decoder;
use Clue\React\NDJson\Encoder;
use React\EventLoop\StreamSelectLoop;
use React\Socket\ConnectionInterface;
use React\Socket\TcpConnector;
use Rector\Application\ApplicationFileProcessor;
use Rector\Configuration\ConfigurationFactory;
use Rector\Console\ProcessConfigureDecorator;
use Rector\Parallel\ValueObject\Bridge;
use Rector\StaticReflection\DynamicSourceLocatorDecorator;
use Rector\Util\MemoryLimiter;
use Rector\ValueObject\Configuration;
use Rector\ValueObject\Error\SystemError;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\EasyParallel\Enum\Action;
use Symplify\EasyParallel\Enum\ReactCommand;
use Symplify\EasyParallel\Enum\ReactEvent;
use Throwable;
use Webmozart\Assert\Assert;

/**
 * Inspired at: https://github.com/phpstan/phpstan-src/commit/9124c66dcc55a222e21b1717ba5f60771f7dda92
 * https://github.com/phpstan/phpstan-src/blob/c471c7b050e0929daf432288770de673b394a983/src/Command/WorkerCommand.php
 *
 * ↓↓↓
 * https://github.com/phpstan/phpstan-src/commit/b84acd2e3eadf66189a64fdbc6dd18ff76323f67#diff-7f625777f1ce5384046df08abffd6c911cfbb1cfc8fcb2bdeaf78f337689e3e2
 */
final class WorkerCommand extends Command
{
    /**
     * @var string
     */
    private const RESULT = 'result';

    public function __construct(
        private readonly DynamicSourceLocatorDecorator $dynamicSourceLocatorDecorator,
        private readonly ApplicationFileProcessor $applicationFileProcessor,
        private readonly MemoryLimiter $memoryLimiter,
        private readonly ConfigurationFactory $configurationFactory
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('worker');
        $this->setDescription('[INTERNAL] Support for parallel process');

        ProcessConfigureDecorator::decorate($this);

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $configuration = $this->configurationFactory->createFromInput($input);
        $this->memoryLimiter->adjust($configuration);

        $streamSelectLoop = new StreamSelectLoop();
        $parallelIdentifier = $configuration->getParallelIdentifier();

        $tcpConnector = new TcpConnector($streamSelectLoop);

        $promise = $tcpConnector->connect('127.0.0.1:' . $configuration->getParallelPort());
        $promise->then(function (ConnectionInterface $connection) use (
            $parallelIdentifier,
            $configuration,
            $output
        ): void {
            $inDecoder = new Decoder($connection, true, 512, JSON_INVALID_UTF8_IGNORE);
            $outEncoder = new Encoder($connection, JSON_INVALID_UTF8_IGNORE);

            $outEncoder->write([
                ReactCommand::ACTION => Action::HELLO,
                ReactCommand::IDENTIFIER => $parallelIdentifier,
            ]);

            $this->runWorker($outEncoder, $inDecoder, $configuration, $output);
        });

        $streamSelectLoop->run();

        return self::SUCCESS;
    }

    private function runWorker(
        Encoder $encoder,
        Decoder $decoder,
        Configuration $configuration,
        OutputInterface $output
    ): void {
        $this->dynamicSourceLocatorDecorator->addPaths($configuration->getPaths());

        if ($configuration->isDebug()) {
            $preFileCallback = static function (string $filePath) use ($output): void {
                $output->writeln($filePath);
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
        $decoder->on(ReactEvent::DATA, function (array $json) use ($preFileCallback, $encoder, $configuration): void {
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
                null,
                $preFileCallback
            );

            /**
             * this invokes all listeners listening $decoder->on(...) @see \Symplify\EasyParallel\Enum\ReactEvent::DATA
             */
            $encoder->write([
                ReactCommand::ACTION => Action::RESULT,
                self::RESULT => [
                    Bridge::FILE_DIFFS => $processResult->getFileDiffs(),
                    Bridge::FILES_COUNT => count($filePaths),
                    Bridge::SYSTEM_ERRORS => $processResult->getSystemErrors(),
                    Bridge::SYSTEM_ERRORS_COUNT => count($processResult->getSystemErrors()),
                ],
            ]);
        });

        $decoder->on(ReactEvent::ERROR, $handleErrorCallback);
    }
}
