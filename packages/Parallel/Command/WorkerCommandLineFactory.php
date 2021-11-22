<?php

declare(strict_types=1);

namespace Rector\Parallel\Command;

use Rector\ChangesReporting\Output\JsonOutputFormatter;
use Rector\Core\Configuration\Option;
use Rector\Core\Console\Command\ProcessCommand;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @see \Rector\Tests\Parallel\Command\WorkerCommandLineFactoryTest
 */
final class WorkerCommandLineFactory
{
    /**
     * @var string
     */
    private const OPTION_DASHES = '--';

    public function __construct(
        private ProcessCommand $processCommand
    ) {
    }

    public function create(
        string $mainScript,
        string $originalCommandName,
        string $workerCommandName,
        ?string $projectConfigFile,
        InputInterface $input,
        string $identifier,
        int $port
    ): string {
        $commandArguments = array_slice($_SERVER['argv'], 1);
        $args = array_merge([PHP_BINARY, $mainScript], $commandArguments);

        $processCommandArray = [];

        foreach ($args as $arg) {
            // skip command name
            if ($arg === $originalCommandName) {
                break;
            }

            $processCommandArray[] = escapeshellarg($arg);
        }

        $processCommandArray[] = $workerCommandName;
        if ($projectConfigFile !== null) {
            $processCommandArray[] = self::OPTION_DASHES . Option::CONFIG;
            $processCommandArray[] = escapeshellarg($projectConfigFile);
        }

        $processCommandOptions = $this->createProcessCommandOptions($input, $this->getCheckCommandOptionNames());
        $processCommandArray = array_merge($processCommandArray, $processCommandOptions);

        // for TCP local server
        $processCommandArray[] = '--port';
        $processCommandArray[] = $port;

        $processCommandArray[] = '--identifier';
        $processCommandArray[] = escapeshellarg($identifier);

        /** @var string[] $paths */
        $paths = $input->getArgument(Option::SOURCE);
        foreach ($paths as $path) {
            $processCommandArray[] = escapeshellarg($path);
        }

        // set json output
        $processCommandArray[] = self::OPTION_DASHES . Option::OUTPUT_FORMAT;
        $processCommandArray[] = escapeshellarg(JsonOutputFormatter::NAME);

        // disable colors, breaks json_decode() otherwise
        // @see https://github.com/symfony/symfony/issues/1238
        $processCommandArray[] = '--no-ansi';

        return implode(' ', $processCommandArray);
    }

    /**
     * @return string[]
     */
    private function getCheckCommandOptionNames(): array
    {
        $inputDefinition = $this->processCommand->getDefinition();

        $optionNames = [];
        foreach ($inputDefinition->getOptions() as $inputOption) {
            $optionNames[] = $inputOption->getName();
        }

        return $optionNames;
    }

    /**
     * Keeps all options that are allowed in check command options
     *
     * @param string[] $checkCommandOptionNames
     * @return string[]
     */
    private function createProcessCommandOptions(InputInterface $input, array $checkCommandOptionNames): array
    {
        $processCommandOptions = [];

        foreach ($checkCommandOptionNames as $checkCommandOptionName) {
            if ($this->shouldSkipOption($input, $checkCommandOptionName)) {
                continue;
            }

            /** @var bool|string|null $optionValue */
            $optionValue = $input->getOption($checkCommandOptionName);

            // skip clutter
            if ($optionValue === null) {
                continue;
            }

            if (is_bool($optionValue)) {
                if ($optionValue) {
                    $processCommandOptions[] = sprintf('--%s', $checkCommandOptionName);
                }

                continue;
            }

            $processCommandOptions[] = self::OPTION_DASHES . $checkCommandOptionName;
            $processCommandOptions[] = escapeshellarg($optionValue);
        }

        return $processCommandOptions;
    }

    private function shouldSkipOption(InputInterface $input, string $optionName): bool
    {
        if (! $input->hasOption($optionName)) {
            return true;
        }

        // skip output format, not relevant in parallel worker command
        return $optionName === Option::OUTPUT_FORMAT;
    }
}
