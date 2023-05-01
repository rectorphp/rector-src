<?php

declare(strict_types=1);

namespace Rector\Tests\Parallel\Command;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\Core\Configuration\Option;
use Rector\Core\Console\Command\ProcessCommand;
use Rector\Core\Kernel\RectorKernel;
use Rector\Parallel\Command\WorkerCommandLineFactory;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;

final class WorkerCommandLineFactoryTest extends TestCase
{
    /**
     * @var string
     */
    private const COMMAND = 'command';

    /**
     * @var string
     */
    private const DUMMY_MAIN_SCRIPT = 'main_script';

    private WorkerCommandLineFactory $workerCommandLineFactory;

    private ProcessCommand $processCommand;

    protected function setUp(): void
    {
        $rectorKernel = new RectorKernel();
        $container = $rectorKernel->create();

        $this->workerCommandLineFactory = $container->get(WorkerCommandLineFactory::class);
        $this->processCommand = $container->get(ProcessCommand::class);
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
            "'" . PHP_BINARY . "' '" . self::DUMMY_MAIN_SCRIPT . "' '" . $cliInputOptionsAsString . "' worker --port 2000 --identifier 'identifier' 'src' --output-format 'worker' --no-ansi",
        ];

        yield [
            [
                self::COMMAND => 'process',
                Option::SOURCE => ['src'],
                '--' . Option::OUTPUT_FORMAT => ConsoleOutputFormatter::NAME,
            ],
            "'" . PHP_BINARY . "' '" . self::DUMMY_MAIN_SCRIPT . "' '" . $cliInputOptionsAsString . "' worker --port 2000 --identifier 'identifier' 'src' --output-format 'worker' --no-ansi",
        ];

        yield [
            [
                self::COMMAND => 'process',
                Option::SOURCE => ['src'],
                '--' . Option::OUTPUT_FORMAT => ConsoleOutputFormatter::NAME,
                '--' . Option::DEBUG => true,
            ],
            "'" . PHP_BINARY . "' '" . self::DUMMY_MAIN_SCRIPT . "' '" . $cliInputOptionsAsString . "' worker --debug --port 2000 --identifier 'identifier' 'src' --output-format 'worker' --no-ansi",
        ];
    }

    private function prepareProcessCommandDefinition(): InputDefinition
    {
        $inputDefinition = $this->processCommand->getDefinition();

        // not sure why, but the 1st argument "command" is missing; this is needed for a command name
        $arguments = $inputDefinition->getArguments();
        $commandInputArgument = new InputArgument(self::COMMAND, InputArgument::REQUIRED);
        $arguments = array_merge([$commandInputArgument], $arguments);

        $inputDefinition->setArguments($arguments);

        return $inputDefinition;
    }
}
