<?php

declare(strict_types=1);

namespace Rector\Tests\Parallel\Command;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\Configuration\ConfigurationFactory;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Parallel\Command\WorkerCommandLineFactory;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\ValueObject\Configuration;

final class WorkerCommandLineFactoryTest extends AbstractLazyTestCase
{
    private const string DUMMY_MAIN_SCRIPT = 'main_script';

    private const string SPACED_DUMMY_MAIN_SCRIPT = 'C:\Users\P\Desktop\Web Dev\vendor\bin\rector';

    private WorkerCommandLineFactory $workerCommandLineFactory;

    private ConfigurationFactory $configurationFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->workerCommandLineFactory = $this->make(WorkerCommandLineFactory::class);
        $this->configurationFactory = $this->make(ConfigurationFactory::class);
    }

    protected function tearDown(): void
    {
        SimpleParameterProvider::setParameter(Option::IS_RUN_NARROWED, false);
        SimpleParameterProvider::setParameter(Option::SOURCE, []);
    }

    public function testSpacedMainScript(): void
    {
        $configuration = $this->createConfiguration();

        $workerCommandLine = $this->workerCommandLineFactory->create(
            self::SPACED_DUMMY_MAIN_SCRIPT,
            'worker',
            $configuration,
            'identifier',
            2000
        );

        $expectedCommand = $this->normalizeExpectedCommandOutput(sprintf(
            "'%s' '%s' worker --port 2000 --identifier 'identifier' --output-format 'json' --no-ansi 'src'",
            PHP_BINARY,
            self::SPACED_DUMMY_MAIN_SCRIPT
        ));

        $this->assertSame($expectedCommand, $workerCommandLine);
    }

    /**
     * @param array{dryRun?: bool, php?: bool, memoryLimit?: ?string} $options
     */
    #[DataProvider('provideData')]
    public function test(array $options, string $expectedOptionsSegment): void
    {
        $configuration = $this->createConfiguration(
            $options['dryRun'] ?? false,
            $options['php'] ?? false,
            $options['memoryLimit'] ?? null
        );

        $workerCommandLine = $this->workerCommandLineFactory->create(
            self::DUMMY_MAIN_SCRIPT,
            'worker',
            $configuration,
            'identifier',
            2000
        );

        $expectedCommand = $this->normalizeExpectedCommandOutput(sprintf(
            "'%s' '%s' worker%s --port 2000 --identifier 'identifier' --output-format 'json' --no-ansi 'src'",
            PHP_BINARY,
            self::DUMMY_MAIN_SCRIPT,
            $expectedOptionsSegment
        ));

        $this->assertSame($expectedCommand, $workerCommandLine);
    }

    /**
     * @return Iterator<array{array<string, mixed>, string}>
     */
    public static function provideData(): Iterator
    {
        yield [[], ''];
        yield [[
            'dryRun' => true,
        ], ' --dry-run'];
        yield [[
            'php' => true,
        ], ' --php'];
        yield [[
            'memoryLimit' => '-1',
        ], " --memory-limit='-1'"];
    }

    private function createConfiguration(
        bool $dryRun = false,
        bool $php = false,
        ?string $memoryLimit = null
    ): Configuration {
        return $this->configurationFactory->create(
            ['src'],
            $dryRun,
            false,
            false,
            false,
            ConsoleOutputFormatter::NAME,
            [],
            false,
            $php,
            null,
            null,
            false,
            $memoryLimit,
            '',
            '',
            null,
            false,
        );
    }

    private function normalizeExpectedCommandOutput(string $command): string
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return str_replace("'", '"', $command);
        }

        return $command;
    }
}
