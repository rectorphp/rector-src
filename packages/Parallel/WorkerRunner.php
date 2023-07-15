<?php

declare(strict_types=1);

namespace Rector\Parallel;

use Clue\React\NDJson\Decoder;
use Clue\React\NDJson\Encoder;
use Rector\Core\Application\ApplicationFileProcessor;
use Rector\Core\StaticReflection\DynamicSourceLocatorDecorator;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Parallel\ValueObject\Bridge;
use Symplify\EasyParallel\Enum\Action;
use Symplify\EasyParallel\Enum\ReactCommand;
use Symplify\EasyParallel\Enum\ReactEvent;
use Throwable;
use PHPStan\Analyser\NodeScopeResolver;

final class WorkerRunner
{
    /**
     * @var string
     */
    private const RESULT = 'result';


    public function __construct(
        private readonly DynamicSourceLocatorDecorator $dynamicSourceLocatorDecorator,
        private readonly ApplicationFileProcessor $applicationFileProcessor,
        private readonly NodeScopeResolver $nodeScopeResolver
    ) {
    }

    public function run(Encoder $encoder, Decoder $decoder, Configuration $configuration): void
    {
        $this->dynamicSourceLocatorDecorator->addPaths($configuration->getPaths());

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
        $decoder->on(ReactEvent::DATA, function (array $json) use ($encoder, $configuration): void {
            $action = $json[ReactCommand::ACTION];
            if ($action !== Action::MAIN) {
                return;
            }

            /** @var string[] $filePaths */
            $filePaths = $json[Bridge::FILES] ?? [];

            // 1. allow PHPStan to work with static reflection on provided files
            $this->nodeScopeResolver->setAnalysedFiles($filePaths);

            $systemErrorsAndFileDiffs = $this->applicationFileProcessor->processFiles(
                $filePaths,
                $configuration
            );

            /**
             * this invokes all listeners listening $decoder->on(...) @see \Symplify\EasyParallel\Enum\ReactEvent::DATA
             */
            $encoder->write([
                ReactCommand::ACTION => Action::RESULT,
                self::RESULT => [
                    Bridge::FILE_DIFFS => $systemErrorsAndFileDiffs[Bridge::FILE_DIFFS] ?? [],
                    Bridge::FILES_COUNT => count($filePaths),
                    Bridge::SYSTEM_ERRORS => $systemErrorsAndFileDiffs[Bridge::SYSTEM_ERRORS],
                    Bridge::SYSTEM_ERRORS_COUNT => $systemErrorsAndFileDiffs[Bridge::SYSTEM_ERRORS_COUNT],
                ],
            ]);
        });

        $decoder->on(ReactEvent::ERROR, $handleErrorCallback);
    }
}
