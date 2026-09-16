<?php

declare(strict_types=1);

/**
 * Measures Rector's wall time across parallel job sizes.
 *
 * Usage, from the project root:
 *
 *     php rector-jobsize-benchmark.php
 *     php rector-jobsize-benchmark.php --config=rector.php --sizes=16,50,100,150,300
 *     php rector-jobsize-benchmark.php --bin=vendor/bin/rector --timeout=3600
 *
 * Notes on reading the output:
 *
 * - "cached" counts the entries Rector wrote, which is files it analysed and found clean.
 *   Files it would change are NOT cached, so "analysed" adds them back in.
 * - Every run is cold. A warm run is a different question and this script does not ask it.
 * - Job timeout is raised (default 3600 s), because a large jobSize puts more work into one
 *   chunk and the stock 120 s limit aborts the run rather than slowing it down.
 * - Raising that timeout is exactly why this script only measures wall time, not safety. A
 *   real run keeps the 120 s per-child timeout and finite memory, so a large jobSize that
 *   looks fast here is where production hits "child process timed out" or OOM. Read the
 *   fastest size as a curiosity, not as a recommended default - the safe band sits low,
 *   around the stock jobSize of 20.
 */
final class JobSizeBenchmark
{
    private ?string $spawnIniDirectory = null;

    /**
     * @param int[] $jobSizes
     */
    public function __construct(
        private readonly string $projectDirectory,
        private readonly string $configPath,
        private readonly string $rectorBinary,
        private readonly array $jobSizes,
        private readonly int $timeoutSeconds,
        private readonly bool $countProcesses = false,
    ) {
    }

    public function run(): int
    {
        $workingDirectory = sys_get_temp_dir() . '/rector-jobsize-' . bin2hex(random_bytes(4));

        printf(
            "project:  %s\nconfig:   %s\ncores:    %s\nphp:      %s\n\n",
            $this->projectDirectory,
            $this->configPath,
            $this->detectCoreCount(),
            PHP_VERSION
        );

        $rows = [];

        foreach ($this->jobSizes as $jobSize) {
            $rows[] = $this->measure($jobSize, $workingDirectory);
        }

        $this->removeDirectory($workingDirectory);
        $this->printMarkdownTable($rows);

        return 0;
    }

    /**
     * @return array{jobSize: int, seconds: float, exitCode: int, cached: int, changed: int, workers: int}
     */
    private function measure(int $jobSize, string $workingDirectory): array
    {
        $runDirectory = $workingDirectory . '/job-' . $jobSize;
        $cacheDirectory = $runDirectory . '/cache';
        $containerCacheDirectory = $runDirectory . '/container';

        $this->removeDirectory($runDirectory);

        // Rector creates the result cache on demand but REQUIRES the container cache
        // directory to exist, so make both here.
        if (! mkdir($cacheDirectory, 0o777, true) || ! mkdir($containerCacheDirectory, 0o777, true)) {
            throw new RuntimeException('Could not create cache directories under ' . $runDirectory);
        }

        $wrapperConfigPath = $runDirectory . '/rector-wrapper.php';
        file_put_contents($wrapperConfigPath, $this->buildWrapperConfig(
            $jobSize,
            $cacheDirectory,
            $containerCacheDirectory
        ));

        $spawnLogPath = $runDirectory . '/spawns.log';

        $command = sprintf(
            '%s%s %s process --dry-run --no-progress-bar --output-format=json --config=%s 2>&1',
            $this->buildSpawnLoggingEnvironment($spawnLogPath),
            escapeshellarg(PHP_BINARY),
            escapeshellarg($this->rectorBinary),
            escapeshellarg($wrapperConfigPath)
        );

        printf("jobSize %-6d running ... ", $jobSize);

        $startedAt = hrtime(true);
        exec($command, $outputLines, $exitCode);
        $seconds = (hrtime(true) - $startedAt) / 1_000_000_000;

        $changed = $this->parseChangedFiles(implode("\n", $outputLines));
        $cached = $this->countCacheEntries($cacheDirectory);
        $workers = $this->countWorkerProcesses($spawnLogPath);

        printf("%6.1f s   exit %d   analysed %d\n", $seconds, $exitCode, $cached + $changed);

        return [
            'jobSize' => $jobSize,
            'seconds' => $seconds,
            'exitCode' => $exitCode,
            'cached' => $cached,
            'changed' => $changed,
            'workers' => $workers,
        ];
    }

    private function buildWrapperConfig(
        int $jobSize,
        string $cacheDirectory,
        string $containerCacheDirectory
    ): string {
        // The project config is imported, never modified. Calls made after the import win,
        // because each of them writes straight into the parameter bag.
        return sprintf(
            <<<'PHP'
                <?php

                declare(strict_types=1);

                use Rector\Config\RectorConfig;

                return static function (RectorConfig $rectorConfig): void {
                    $rectorConfig->import(%s);
                    $rectorConfig->parallel(%d, %d, %d);
                    $rectorConfig->cacheDirectory(%s);
                    $rectorConfig->containerCacheDirectory(%s);
                };
                PHP,
            var_export($this->configPath, true),
            $this->timeoutSeconds,
            $this->detectCoreCount(),
            $jobSize,
            var_export($cacheDirectory, true),
            var_export($containerCacheDirectory, true)
     );
    }

    /**
     * Counts how many PHP processes Rector starts, by prepending a one-line logger to every
     * one of them. Rector spawns its workers as plain `php` processes, so the only way to
     * reach them is through the environment they inherit - hence PHP_INI_SCAN_DIR rather
     * than a `-d` flag, which would apply to the parent only.
     *
     * The logger is a single `file_put_contents` per process, which is noise next to a
     * process start, but it is opt-in so that the default timings carry nothing extra.
     */
    private function buildSpawnLoggingEnvironment(string $spawnLogPath): string
    {
        if (! $this->countProcesses) {
            return '';
        }

        if ($this->spawnIniDirectory === null) {
            $this->spawnIniDirectory = dirname($spawnLogPath, 2) . '/spawn-ini';
            mkdir($this->spawnIniDirectory, 0o777, true);

            $loggerPath = $this->spawnIniDirectory . '/spawn-logger.php';
            file_put_contents($loggerPath, <<<'PHP'
                <?php
                $log = getenv('RECTOR_BENCH_SPAWN_LOG');
                if (is_string($log) && $log !== '') {
                    $isWorker = in_array('worker', $_SERVER['argv'] ?? [], true) ? 'worker' : 'main';
                    file_put_contents($log, $isWorker . "\n", FILE_APPEND | LOCK_EX);
                }
                PHP);

            file_put_contents(
                $this->spawnIniDirectory . '/zz-spawn.ini',
                'auto_prepend_file=' . $loggerPath . "\n"
            );
        }

        return sprintf(
            'PHP_INI_SCAN_DIR=%s RECTOR_BENCH_SPAWN_LOG=%s ',
            escapeshellarg(':' . $this->spawnIniDirectory),
            escapeshellarg($spawnLogPath)
        );
    }

    private function countWorkerProcesses(string $spawnLogPath): int
    {
        if (! is_file($spawnLogPath)) {
            return 0;
        }

        $contents = file_get_contents($spawnLogPath);
        if (! is_string($contents)) {
            return 0;
        }

        return substr_count($contents, "worker\n");
    }

    private function parseChangedFiles(string $output): int
    {
        if (preg_match('/"changed_files"\s*:\s*(\d+)/', $output, $matches) === 1) {
            return (int) $matches[1];
        }

        return 0;
    }

    private function countCacheEntries(string $cacheDirectory): int
    {
        if (! is_dir($cacheDirectory)) {
            return 0;
        }

        $count = 0;
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($cacheDirectory, FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $fileInfo) {
            if (! $fileInfo instanceof SplFileInfo || $fileInfo->getExtension() !== 'php') {
                continue;
            }

            // the cache also holds one configuration-hash entry, which is not a file
            $contents = file_get_contents($fileInfo->getPathname());
            if (is_string($contents) && str_contains($contents, 'file_hash')) {
                ++$count;
            }
        }

        return $count;
    }

    /**
     * @param array<array{jobSize: int, seconds: float, exitCode: int, cached: int, changed: int, workers: int}> $rows
     */
    private function printMarkdownTable(array $rows): void
    {
        $fastest = min(array_column($rows, 'seconds'));
        $workerColumn = $this->countProcesses ? ' worker processes |' : '';
        $workerDivider = $this->countProcesses ? ' --: |' : '';

        echo "\n| `jobSize` | wall | vs fastest |" . $workerColumn . " exit | analysed | of which changed |\n";
        echo "| --: | --: | --: |" . $workerDivider . " --: | --: | --: |\n";

        foreach ($rows as $row) {
            printf(
                "| %d | %.1f s | %.2fx |%s %d | %d | %d |\n",
                $row['jobSize'],
                $row['seconds'],
                $row['seconds'] / $fastest,
                $this->countProcesses ? ' ' . $row['workers'] . ' |' : '',
                $row['exitCode'],
                $row['cached'] + $row['changed'],
                $row['changed']
            );
        }

        echo "\nEvery row is a cold run. `analysed` should be identical across rows - if a row is\n";
        echo "lower, that run silently dropped work rather than doing it faster.\n";
    }

    private function detectCoreCount(): int
    {
        $shellCoreCount = @shell_exec('getconf _NPROCESSORS_ONLN 2>/dev/null || sysctl -n hw.ncpu 2>/dev/null');
        $coreCount = is_string($shellCoreCount) ? (int) trim($shellCoreCount) : 0;

        return $coreCount > 0 ? $coreCount : 16;
    }

    private function removeDirectory(string $directory): void
    {
        if (! is_dir($directory)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $fileInfo) {
            if (! $fileInfo instanceof SplFileInfo) {
                continue;
            }

            $fileInfo->isDir() ? rmdir($fileInfo->getPathname()) : unlink($fileInfo->getPathname());
        }

        rmdir($directory);
    }
}

// ---------------------------------------------------------------------------------------

$options = getopt('', ['config::', 'sizes::', 'bin::', 'timeout::', 'processes']);

$projectDirectory = getcwd();
if ($projectDirectory === false) {
    fwrite(STDERR, "Cannot resolve the working directory.\n");
    exit(1);
}

$configPath = realpath((string) ($options['config'] ?? 'rector.php'));
if ($configPath === false) {
    fwrite(STDERR, "Config not found. Pass --config=path/to/rector.php\n");
    exit(1);
}

$rectorBinary = realpath((string) ($options['bin'] ?? 'vendor/bin/rector'));
if ($rectorBinary === false) {
    fwrite(STDERR, "Rector binary not found. Pass --bin=path/to/rector\n");
    exit(1);
}

$jobSizes = array_map(
    static fn (string $size): int => (int) trim($size),
    explode(',', (string) ($options['sizes'] ?? '16,50,100,150,300'))
);

$jobSizes = array_values(array_filter($jobSizes, static fn (int $size): bool => $size > 0));
if ($jobSizes === []) {
    fwrite(STDERR, "No valid job sizes given.\n");
    exit(1);
}

$benchmark = new JobSizeBenchmark(
    $projectDirectory,
    $configPath,
    $rectorBinary,
    $jobSizes,
    (int) ($options['timeout'] ?? 3600),
    array_key_exists('processes', $options)
);

exit($benchmark->run());
