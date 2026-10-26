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
            'expectedOutput' => PHP_EOL . " [ERROR] Unexpected item 'parameters › invalidParameters'. " . PHP_EOL . PHP_EOL,
        ];
        yield 'Exception with previous console output in JSON format' => [
            'command' => PHP_BINARY . ' bin/rector -c tests/Bin/config/incorrect-phpstan-files.php --output-format json',
            'expectedOutput' => '{"fatal_errors":["Unexpected item \'parameters › invalidParameters\'."]}',
        ];
    }

    #[DataProvider('outputProvider')]
    public function testConsoleOutput(string $command, string $expectedOutput): void
    {
        $process = Process::fromShellCommandline($command);
        $process->run();
        $this->assertSame($expectedOutput, preg_replace('/ +/', ' ', $process->getOutput()), sprintf(
            'exit code %s, stderr: %s',
            $process->getExitCode() ?? 'not started',
            $process->getErrorOutput()
        ));
    }

    /**
     * @return Iterator<string, array{int, string[]}>
     */
    public static function provideParallelTimeouts(): Iterator
    {
        yield 'one timeout below the limit' => [0, [
            'Child process timed out after 1 seconds',
            'Some parallel jobs have not finished, results for 1 of 1 files are missing',
        ]];

        yield '49 errors followed by a timeout reach the limit' => [49, [
            ...array_fill(0, 49, "Syntax error, unexpected ';'"),
            'Child process timed out after 1 seconds',
            'Reached system errors count limit of 50, exiting...',
            'Some parallel jobs have not finished, results for 1 of 50 files are missing',
        ]];
    }

    /**
     * @param string[] $expectedMessages
     */
    #[DataProvider('provideParallelTimeouts')]
    public function testParallelTimeoutReportsErrorLimitOnlyWhenReached(
        int $previousErrorCount,
        array $expectedMessages
    ): void {
        $filePaths = [];
        for ($i = 5; $i < 5 + $previousErrorCount; ++$i) {
            $filePaths[] = __DIR__ . sprintf('/../../e2e/parallel-system-errors-limit/src/File%02d.php', $i);
        }

        // The syntax-error files are larger, so the single worker processes them before the timeout file.
        $filePaths[] = __DIR__ . '/Fixture/ParallelTimeout/Timeout.php';

        $process = new Process([
            PHP_BINARY,
            'bin/rector',
            '--config',
            __DIR__ . '/config/parallel-worker-timeout.php',
            '--dry-run',
            '--clear-cache',
            '--no-progress-bar',
            '--output-format=json',
            ...$filePaths,
        ]);
        $process->setTimeout(20);
        $process->run();

        $this->assertSame(1, $process->getExitCode(), $process->getOutput() . $process->getErrorOutput());
        $result = json_decode($process->getOutput(), true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame(count($expectedMessages), $result['totals']['errors']);
        $this->assertSame($expectedMessages, array_column($result['errors'], 'message'));
    }
}
