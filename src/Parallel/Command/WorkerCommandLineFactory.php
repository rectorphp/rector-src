<?php

declare(strict_types=1);

namespace Rector\Parallel\Command;

use Rector\ChangesReporting\Output\JsonOutputFormatter;
use Rector\Configuration\Option;
use Rector\FileSystem\FilePathHelper;
use Rector\ValueObject\Configuration;

/**
 * @see \Rector\Tests\Parallel\Command\WorkerCommandLineFactoryTest
 */
final readonly class WorkerCommandLineFactory
{
    private const string OPTION_DASHES = '--';

    public function __construct(
        private FilePathHelper $filePathHelper
    ) {
    }

    /**
     * Build the worker subprocess command line from the resolved Configuration.
     * Options mirror the Entropy run() contract: a flag is "--name", a value option "--name value".
     */
    public function create(
        string $mainScript,
        string $workerCommandName,
        Configuration $configuration,
        string $identifier,
        int $port,
    ): string {
        $workerCommandArray = [escapeshellarg(PHP_BINARY), escapeshellarg($mainScript), $workerCommandName];

        if ($configuration->isDryRun()) {
            $workerCommandArray[] = self::OPTION_DASHES . Option::DRY_RUN;
        }

        if ($configuration->shouldClearCache()) {
            $workerCommandArray[] = self::OPTION_DASHES . Option::CLEAR_CACHE;
        }

        if ($configuration->isComposerBased()) {
            $workerCommandArray[] = self::OPTION_DASHES . Option::COMPOSER_BASED;
        }

        if ($configuration->isPhpOnly()) {
            $workerCommandArray[] = self::OPTION_DASHES . Option::PHP;
        }

        // narrow the run to the same rules
        foreach ($configuration->getOnlyRules() as $onlyRule) {
            $workerCommandArray[] = self::OPTION_DASHES . Option::ONLY;
            $workerCommandArray[] = escapeshellarg($onlyRule);
        }

        $onlySuffix = $configuration->getOnlySuffix();
        if ($onlySuffix !== null && $onlySuffix !== '') {
            $workerCommandArray[] = self::OPTION_DASHES . Option::ONLY_SUFFIX;
            $workerCommandArray[] = escapeshellarg($onlySuffix);
        }

        $memoryLimit = $configuration->getMemoryLimit();
        if ($memoryLimit !== null && $memoryLimit !== '') {
            // "=" form, so a negative value like -1 is not parsed as another option
            $workerCommandArray[] = self::OPTION_DASHES . Option::MEMORY_LIMIT . '=' . escapeshellarg($memoryLimit);
        }

        // for TCP local server
        $workerCommandArray[] = self::OPTION_DASHES . Option::PARALLEL_PORT;
        $workerCommandArray[] = (string) $port;

        $workerCommandArray[] = self::OPTION_DASHES . Option::PARALLEL_IDENTIFIER;
        $workerCommandArray[] = escapeshellarg($identifier);

        // set json output
        $workerCommandArray[] = self::OPTION_DASHES . Option::OUTPUT_FORMAT;
        $workerCommandArray[] = escapeshellarg(JsonOutputFormatter::NAME);

        // disable colors, breaks json_decode() otherwise
        // @see https://github.com/symfony/symfony/issues/1238
        $workerCommandArray[] = '--no-ansi';

        // only pass --config if explicitly set via command line; otherwise the worker resolves it
        // through the RectorConfigsResolver fallback, same as the main process
        $config = $this->resolveConfigFromArgv();
        if ($config !== null) {
            $workerCommandArray[] = self::OPTION_DASHES . Option::CONFIG;
            $workerCommandArray[] = escapeshellarg($this->filePathHelper->relativePath($config));
        }

        // paths last, as positional arguments
        foreach ($configuration->getPaths() as $path) {
            $workerCommandArray[] = escapeshellarg($path);
        }

        return implode(' ', $workerCommandArray);
    }

    /**
     * Resolve "--config <file>", "--config=<file>", "-c <file>" or "-c=<file>" from raw argv.
     */
    private function resolveConfigFromArgv(): ?string
    {
        $argv = $_SERVER['argv'] ?? [];

        foreach ($argv as $index => $arg) {
            if ($arg === self::OPTION_DASHES . Option::CONFIG || $arg === '-c') {
                $nextArg = $argv[$index + 1] ?? null;
                return is_string($nextArg) ? $nextArg : null;
            }

            if (is_string($arg) && str_starts_with($arg, self::OPTION_DASHES . Option::CONFIG . '=')) {
                return substr($arg, strlen(self::OPTION_DASHES . Option::CONFIG . '='));
            }

            if (is_string($arg) && str_starts_with($arg, '-c=')) {
                return substr($arg, strlen('-c='));
            }
        }

        return null;
    }
}
