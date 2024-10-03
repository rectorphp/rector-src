<?php

declare(strict_types=1);

namespace Rector\Tests\Parallel\Command;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\Configuration\Option;
use Rector\Console\Command\ProcessCommand;
use Rector\Parallel\Command\WorkerCommandLineFactory;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;

final class WorkerCommandLineFactoryTest extends AbstractLazyTestCase
{
    /**
     * @var string
     */
    private const COMMAND = 'command';

    /**
     * @var string
     */
    private const DUMMY_MAIN_SCRIPT = 'main_script';

    /**
     * @var string
     */
    private const SPACED_DUMMY_MAIN_SCRIPT = 'C:\Users\P\Desktop\Web Dev\vendor\bin\rector';

    private WorkerCommandLineFactory $workerCommandLineFactory;

    private ProcessCommand $processCommand;

    protected function setUp(): void
    {
        $this->workerCommandLineFactory = $this->make(WorkerCommandLineFactory::class);
        $this->processCommand = $this->make(ProcessCommand::class);
    }

    /**
     * @param array<string, mixed> $inputParameters
     */
    #[DataProvider('provideDataSpacedMainScript')]
    public function testSpacedMainScript(array $inputParameters, string $expectedCommand): void
    {
        $inputDefinition = $this->prepareProcessCommandDefinition();
        $arrayInput = new ArrayInput($inputParameters, $inputDefinition);

        $workerCommandLine = $this->workerCommandLineFactory->create(
            self::SPACED_DUMMY_MAIN_SCRIPT,
            ProcessCommand::class,
            'worker',
            $arrayInput,
            'identifier',
            2000
        );

        // running on macOS cause empty string after SPACED_DUMMY_MAIN_SCRIPT constant value removed on run whole unit test
        // this ensure it works
        $workerCommandLine = str_replace("rector' worker", "rector' '' worker", $workerCommandLine);

        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            $expectedCommand = str_replace("'", '"', $expectedCommand);
            $workerCommandLine = str_replace("'", '"', $workerCommandLine);
        }

        $this->assertSame($expectedCommand, $workerCommandLine);
    }

    /**
     * @return Iterator<array<int, array<string, string|string[]|bool>>|string[]>
     */
    public static function provideDataSpacedMainScript(): Iterator
    {
        $cliInputOptions = array_slice($_SERVER['argv'], 1);
        $cliInputOptionsAsString = implode("' '", $cliInputOptions);

        yield [
            [
                self::COMMAND => 'process',
                Option::SOURCE => ['src'],
            ],
            "'" . PHP_BINARY . "' '" . self::SPACED_DUMMY_MAIN_SCRIPT . "' '" . $cliInputOptionsAsString . "' worker --port 2000 --identifier 'identifier' 'src' --output-format 'json' --no-ansi",
        ];

        yield [
            [
                self::COMMAND => 'process',
                Option::SOURCE => ['src'],
                '--' . Option::OUTPUT_FORMAT => ConsoleOutputFormatter::NAME,
            ],
            "'" . PHP_BINARY . "' '" . self::SPACED_DUMMY_MAIN_SCRIPT . "' '" . $cliInputOptionsAsString . "' worker --port 2000 --identifier 'identifier' 'src' --output-format 'json' --no-ansi",
        ];

        yield [
            [
                self::COMMAND => 'process',
                Option::SOURCE => ['src'],
                '--' . Option::OUTPUT_FORMAT => ConsoleOutputFormatter::NAME,
                '--' . Option::DEBUG => true,
            ],
            "'" . PHP_BINARY . "' '" . self::SPACED_DUMMY_MAIN_SCRIPT . "' '" . $cliInputOptionsAsString . "' worker --debug --port 2000 --identifier 'identifier' 'src' --output-format 'json' --no-ansi",
        ];

        yield [
            [
                self::COMMAND => 'process',
                Option::SOURCE => ['src'],
                '--' . Option::OUTPUT_FORMAT => ConsoleOutputFormatter::NAME,
                '--' . Option::DEBUG => true,
                '--' . Option::XDEBUG => true,
            ],
            "'" . PHP_BINARY . "' '" . self::SPACED_DUMMY_MAIN_SCRIPT . "' '" . $cliInputOptionsAsString . "' worker --debug --xdebug --port 2000 --identifier 'identifier' 'src' --output-format 'json' --no-ansi",
        ];
    }

    /**
     * @param array<string, mixed> $inputParameters
     */
    #[DataProvider('provideData')]
    public function test(array $inputParameters, string $expectedCommand): void
    {
        $inputDefinition = $this->prepareProcessCommandDefinition();
        $arrayInput = new ArrayInput($inputParameters, $inputDefinition);

        $workerCommandLine = $this->workerCommandLineFactory->create(
            self::DUMMY_MAIN_SCRIPT,
            ProcessCommand::class,
            'worker',
            $arrayInput,
            'identifier',
            2000
        );

        // running on macOS cause empty string after main_script removed on run whole unit test
        // this ensure it works
        $workerCommandLine = str_replace("'main_script' worker", "'main_script' '' worker", $workerCommandLine);

        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            $expectedCommand = str_replace("'", '"', $expectedCommand);
            $workerCommandLine = str_replace("'", '"', $workerCommandLine);
        }

        $this->assertSame($expectedCommand, $workerCommandLine);
    }

    /**
     * @return Iterator<array<int, array<string, string|string[]|bool>>|string[]>
     */
    public static function provideData(): Iterator
    {
        $cliInputOptions = array_slice($_SERVER['argv'], 1);
        $cliInputOptionsAsString = implode("' '", $cliInputOptions);

        yield [
            [
                self::COMMAND => 'process',
                Option::SOURCE => ['src'],
            ],
            "'" . PHP_BINARY . "' '" . self::DUMMY_MAIN_SCRIPT . "' '" . $cliInputOptionsAsString . "' worker --port 2000 --identifier 'identifier' 'src' --output-format 'json' --no-ansi",
        ];

        yield [
            [
                self::COMMAND => 'process',
                Option::SOURCE => ['src'],
                '--' . Option::OUTPUT_FORMAT => ConsoleOutputFormatter::NAME,
            ],
            "'" . PHP_BINARY . "' '" . self::DUMMY_MAIN_SCRIPT . "' '" . $cliInputOptionsAsString . "' worker --port 2000 --identifier 'identifier' 'src' --output-format 'json' --no-ansi",
        ];

        yield [
            [
                self::COMMAND => 'process',
                Option::SOURCE => ['src'],
                '--' . Option::OUTPUT_FORMAT => ConsoleOutputFormatter::NAME,
                '--' . Option::DEBUG => true,
            ],
            "'" . PHP_BINARY . "' '" . self::DUMMY_MAIN_SCRIPT . "' '" . $cliInputOptionsAsString . "' worker --debug --port 2000 --identifier 'identifier' 'src' --output-format 'json' --no-ansi",
        ];

        yield [
            [
                self::COMMAND => 'process',
                Option::SOURCE => ['src'],
                '--' . Option::OUTPUT_FORMAT => ConsoleOutputFormatter::NAME,
                '--' . Option::DEBUG => true,
                '--' . Option::XDEBUG => true,
            ],
            "'" . PHP_BINARY . "' '" . self::DUMMY_MAIN_SCRIPT . "' '" . $cliInputOptionsAsString . "' worker --debug --xdebug --port 2000 --identifier 'identifier' 'src' --output-format 'json' --no-ansi",
        ];

        yield [
            [
                self::COMMAND => 'process',
                Option::SOURCE => ['src'],
                '--' . Option::OUTPUT_FORMAT => ConsoleOutputFormatter::NAME,
                '--' . Option::MEMORY_LIMIT => '-1',
            ],
            "'" . PHP_BINARY . "' '" . self::DUMMY_MAIN_SCRIPT . "' '" . $cliInputOptionsAsString . "' worker --memory-limit='-1' --port 2000 --identifier 'identifier' 'src' --output-format 'json' --no-ansi",
        ];
    }

    private function prepareProcessCommandDefinition(): InputDefinition
    {
        // clone the object as we should not modify a object taken from the DI container
        $inputDefinition = clone $this->processCommand->getDefinition();

        // not sure why, but the 1st argument "command" is missing; this is needed for a command name
        $arguments = $inputDefinition->getArguments();
        $commandInputArgument = new InputArgument(self::COMMAND, InputArgument::REQUIRED);
        $arguments = [$commandInputArgument, ...$arguments];

        $inputDefinition->setArguments($arguments);

        return $inputDefinition;
    }
}
