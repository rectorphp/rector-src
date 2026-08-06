<?php

declare(strict_types=1);

namespace Rector\Tests\Bin;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

final class RectorTest extends TestCase
{
    /**
     * @return Iterator<string, array{command: string, expectedOutput: string}>
     */
    public static function outputProvider(): Iterator
    {
        yield 'Version' => [
            'command' => PHP_BINARY . ' bin/rector --version',
            'expectedOutput' => 'Rector @package_version@' . PHP_EOL,
        ];
        yield 'Exception with previous console output' => [
            'command' => PHP_BINARY . ' bin/rector -c tests/Bin/config/incorrect-phpstan-files.php',
            'expectedOutput' => PHP_EOL . ' [ERROR] Rector\\NodeTypeResolver\\DependencyInjection\\PHPStanServicesFactory ' . PHP_EOL . PHP_EOL . " [ERROR] Unexpected item 'parameters › invalidParameters'. " . PHP_EOL . PHP_EOL,
        ];
        yield 'Exception with previous console output in JSON format' => [
            'command' => PHP_BINARY . ' bin/rector -c tests/Bin/config/incorrect-phpstan-files.php --output-format json',
            'expectedOutput' => '{"fatal_errors":["Rector\\\\NodeTypeResolver\\\\DependencyInjection\\\\PHPStanServicesFactory","Unexpected item \'parameters › invalidParameters\'."]}',
        ];
    }

    #[DataProvider('outputProvider')]
    public function testConsoleOutput(string $command, string $expectedOutput): void
    {
        $process = Process::fromShellCommandline($command);
        $process->run();
        $this->assertSame($expectedOutput, preg_replace('/ +/', ' ', $process->getOutput()));
    }
}
