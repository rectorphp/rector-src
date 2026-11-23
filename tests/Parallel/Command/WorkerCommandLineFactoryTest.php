<?php

declare(strict_types=1);

namespace Rector\Tests\Parallel\Command;

use Iterator;
use PHPStan\Turbo\TurboExtensionSelector;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Parallel\Command\WorkerCommandLineFactory;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\ValueObject\Configuration;

final class WorkerCommandLineFactoryTest extends AbstractLazyTestCase
{
    private const string DUMMY_MAIN_SCRIPT = 'main_script';

    private const string SPACED_DUMMY_MAIN_SCRIPT = 'C:\Users\P\Desktop\Web Dev\vendor\bin\rector';

    private WorkerCommandLineFactory $workerCommandLineFactory;

    /**
     * @var array<int, mixed>
     */
    private array $originalArgv = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->workerCommandLineFactory = $this->make(WorkerCommandLineFactory::class);
        $this->originalArgv = $_SERVER['argv'];
    }

    protected function tearDown(): void
    {
        $_SERVER['argv'] = $this->originalArgv;
    }

    public function testSpacedMainScript(): void
    {
        $_SERVER['argv'] = ['bin/rector', 'process', 'src'];

        $workerCommandLine = $this->workerCommandLineFactory->create(
            self::SPACED_DUMMY_MAIN_SCRIPT,
            'worker',
            new Configuration(paths: ['src']),
            'identifier',
            2000
        );

        $expectedCommand = $this->normalizeExpectedCommandOutput(
            "'" . PHP_BINARY . "' " . $this->turboExtensionArguments() . "'" . self::SPACED_DUMMY_MAIN_SCRIPT . "' worker --port 2000 --identifier 'identifier' 'src' --output-format 'json' --no-ansi"
        );

        $this->assertSame($expectedCommand, $workerCommandLine);
    }

    /**
     * @param array<int, string> $argv
     */
    #[DataProvider('provideData')]
    public function test(array $argv, Configuration $configuration, string $expectedOptions): void
    {
        $_SERVER['argv'] = $argv;

        $workerCommandLine = $this->workerCommandLineFactory->create(
            self::DUMMY_MAIN_SCRIPT,
            'worker',
            $configuration,
            'identifier',
            2000
        );

        $expectedCommand = $this->normalizeExpectedCommandOutput(
            "'" . PHP_BINARY . "' " . $this->turboExtensionArguments() . "'" . self::DUMMY_MAIN_SCRIPT . "' worker " . $expectedOptions
        );

        $this->assertSame($expectedCommand, $workerCommandLine);
    }

    /**
     * @return Iterator<array{array<int, string>, Configuration, string}>
     */
    public static function provideData(): Iterator
    {
        $defaultArgv = ['bin/rector', 'process', 'src'];
        $suffix = "--port 2000 --identifier 'identifier' 'src' --output-format 'json' --no-ansi";

        yield 'no options' => [$defaultArgv, new Configuration(paths: ['src']), $suffix];

        yield 'flags' => [
            [...$defaultArgv, '--xdebug'],
            new Configuration(
                isDryRun: true,
                shouldClearCache: true,
                paths: ['src'],
                showDiffs: false,
                isDebug: true,
                isComposerBased: true,
                isPhpOnly: true,
            ),
            '--dry-run --clear-cache --no-diffs --composer-based --php --debug --xdebug ' . $suffix,
        ];

        yield 'autoload file' => [
            [...$defaultArgv, '--autoload-file=some/autoload.php'],
            new Configuration(paths: ['src']),
            "--autoload-file 'some/autoload.php' " . $suffix,
        ];

        yield 'short autoload file' => [
            [...$defaultArgv, '-a', 'some/autoload.php'],
            new Configuration(paths: ['src']),
            "--autoload-file 'some/autoload.php' " . $suffix,
        ];

        yield 'value options' => [
            $defaultArgv,
            new Configuration(
                paths: ['src'],
                memoryLimit: '-1',
                onlyRules: ['Rector\SomeRector', 'Rector\AnotherRector'],
                onlySuffix: 'Controller.php',
                filters: ['/Controller/', '*Repository.php'],
                maxChanges: 5,
            ),
            "--only 'Rector\SomeRector' --only 'Rector\AnotherRector' --only-suffix 'Controller.php' --filter '/Controller/,*Repository.php' --memory-limit='-1' --max-changes '5' " . $suffix,
        ];

        yield 'explicit config' => [
            [...$defaultArgv, '--config', 'some config/rector.php'],
            new Configuration(paths: ['src']),
            $suffix . " --config 'some config/rector.php'",
        ];
    }

    /**
     * The worker gets PHPStan Turbo through two -d options, when PHPStan ships a binary for this runtime
     */
    private function turboExtensionArguments(): string
    {
        $extensionPath = TurboExtensionSelector::findExtensionForWorkers();
        if ($extensionPath === null) {
            return '';
        }

        $arguments = [
            '-d',
            "extension='" . $extensionPath . "'",
            '-d',
            TurboExtensionSelector::EXTENSION_PATH_INI . "='" . $extensionPath . "'",
        ];

        return implode(' ', array_map(escapeshellarg(...), $arguments)) . ' ';
    }

    private function normalizeExpectedCommandOutput(string $command): string
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            // the turbo arguments are escaped by escapeshellarg() already, keep the single quotes inside them
            $turboExtensionArguments = $this->turboExtensionArguments();

            return str_replace(
                str_replace("'", '"', $turboExtensionArguments),
                $turboExtensionArguments,
                str_replace("'", '"', $command)
            );
        }

        return $command;
    }
}
