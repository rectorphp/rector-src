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
            'command' => 'bin/rector --version',
            'expectedOutput' => "Rector @package_version@\n",
        ];
        yield 'Exception with previous console output' => [
            'command' => 'bin/rector -c tests/Bin/config/incorrect-phpstan-files.php',
            'expectedOutput' => "\n [ERROR] Rector\\NodeTypeResolver\\DependencyInjection\\PHPStanServicesFactory     \n\n [ERROR] Unexpected item 'parameters › invalidParameters'.                      \n\n",
        ];
        yield 'Exception with previous console output in JSON format' => [
            'command' => 'bin/rector -c tests/Bin/config/incorrect-phpstan-files.php --output-format json',
            'expectedOutput' => '{"fatal_errors":["Rector\\\\NodeTypeResolver\\\\DependencyInjection\\\\PHPStanServicesFactory","Unexpected item \'parameters › invalidParameters\'."]}',
        ];
    }

    #[DataProvider('outputProvider')]
    public function testConsoleOutput(string $command, string $expectedOutput): void
    {
        $process = Process::fromShellCommandline($command);
        $process->run();
        $this->assertSame($expectedOutput, $process->getOutput());
    }
}
