<?php

declare(strict_types=1);

/**
 * A/B benchmark for Rector performance changes.
 *
 * Measures the CURRENT checkout only - it never switches git refs, so it is safe to run
 * while other work happens in the same repository. To compare two refs, run it once per
 * ref and point the second run at the first run's saved results:
 *
 *     git checkout main
 *     php scripts/rector-ab-benchmark.php --save=/tmp/baseline.json
 *     git checkout my-perf-branch
 *     php scripts/rector-ab-benchmark.php --baseline=/tmp/baseline.json
 *
 * Options:
 *
 *     --project=PATH    project to analyse (default: shallow-clone laravel/laravel to temp)
 *     --runs=N          timed runs per phase, the median is reported (default: 3)
 *     --save=FILE       write this run's numbers as JSON
 *     --baseline=FILE   print an A/B table against a previously saved JSON
 *     --bin=PATH        rector binary (default: bin/rector in this checkout)
 *
 * What it reports, for a cold phase (cache cleared before every run) and a warm phase
 * (cache primed once, then reused):
 *
 * - median wall time
 * - peak resident memory, when /usr/bin/time is available (Linux)
 * - changed-file count AND a fingerprint of the sorted changed-file list. The fingerprint
 *   must stay identical across refs - a perf change that alters it changed behaviour, not
 *   just speed.
 */
final readonly class AbBenchmark
{
    private const string LARAVEL_REPOSITORY = 'https://github.com/laravel/laravel.git';

    public function __construct(
        private string $projectDirectory,
        private string $rectorBinary,
        private int $runs,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function run(): array
    {
        $workingDirectory = sys_get_temp_dir() . '/rector-ab-' . bin2hex(random_bytes(4));
        $cacheDirectory = $workingDirectory . '/cache';
        $containerCacheDirectory = $workingDirectory . '/container';

        if (! mkdir($cacheDirectory, 0o777, true) || ! mkdir($containerCacheDirectory, 0o777, true)) {
            throw new RuntimeException('Could not create cache directories under ' . $workingDirectory);
        }

        $configPath = $workingDirectory . '/rector-ab-config.php';
        file_put_contents($configPath, $this->buildConfig($cacheDirectory, $containerCacheDirectory));

        printf(
            "project:  %s\nbinary:   %s\nphp:      %s\nruns:     %d\n\n",
            $this->projectDirectory,
            $this->rectorBinary,
            PHP_VERSION,
            $this->runs
        );

        $cold = $this->measurePhase('cold', $configPath, $cacheDirectory, true);
        $warm = $this->measurePhase('warm', $configPath, $cacheDirectory, false);

        $this->removeDirectory($workingDirectory);

        return [
            'php' => PHP_VERSION,
            'runs' => $this->runs,
            'cold' => $cold,
            'warm' => $warm,
        ];
    }

    /**
     * @param array<string, mixed> $result
     * @param array<string, mixed>|null $baseline
     */
    public function report(array $result, ?array $baseline): void
    {
        echo "\n";

        foreach (['cold', 'warm'] as $phase) {
            /** @var array<string, mixed> $current */
            $current = $result[$phase];
            $this->printPhase($phase, $current, is_array($baseline) ? $baseline[$phase] ?? null : null);
        }
    }

    /**
     * @return array{seconds: float, peakKb: int, changed: int, fingerprint: string}
     */
    private function measurePhase(string $phase, string $configPath, string $cacheDirectory, bool $cold): array
    {
        if (! $cold) {
            // prime the cache once, so the measured runs read a warm result cache
            $this->runRector($configPath, $cacheDirectory, false);
        }

        $durations = [];
        $peakKb = 0;
        $changed = 0;
        $fingerprint = '';

        for ($i = 0; $i < $this->runs; ++$i) {
            printf('%-5s run %d/%d ... ', $phase, $i + 1, $this->runs);

            $measurement = $this->runRector($configPath, $cacheDirectory, $cold);

            $durations[] = $measurement['seconds'];
            $peakKb = max($peakKb, $measurement['peakKb']);
            $changed = $measurement['changed'];
            $fingerprint = $measurement['fingerprint'];

            printf("%6.2f s   changed %d\n", $measurement['seconds'], $measurement['changed']);
        }

        return [
            'seconds' => $this->median($durations),
            'peakKb' => $peakKb,
            'changed' => $changed,
            'fingerprint' => $fingerprint,
        ];
    }

    /**
     * @return array{seconds: float, peakKb: int, changed: int, fingerprint: string}
     */
    private function runRector(string $configPath, string $cacheDirectory, bool $cold): array
    {
        if ($cold) {
            $this->clearDirectoryContents($cacheDirectory);
        }

        $rectorCommand = sprintf(
            '%s %s process --dry-run --no-progress-bar --output-format=json --config=%s',
            escapeshellarg(PHP_BINARY),
            escapeshellarg($this->rectorBinary),
            escapeshellarg($configPath)
        );

        $timeOutputPath = sys_get_temp_dir() . '/rector-ab-time-' . bin2hex(random_bytes(4));
        $usesGnuTime = $this->hasGnuTime();

        // GNU time writes its -v report to the -o file; rector errors are dropped to keep stdout clean JSON
        $command = $usesGnuTime
            ? sprintf('/usr/bin/time -v -o %s %s 2>/dev/null', escapeshellarg($timeOutputPath), $rectorCommand)
            : $rectorCommand . ' 2>/dev/null';

        $startedAt = hrtime(true);
        $jsonOutput = shell_exec($command);
        $seconds = (hrtime(true) - $startedAt) / 1_000_000_000;

        $peakKb = $usesGnuTime ? $this->parsePeakKb($timeOutputPath) : 0;
        @unlink($timeOutputPath);

        $changedFiles = $this->parseChangedFiles(is_string($jsonOutput) ? $jsonOutput : '');

        return [
            'seconds' => $seconds,
            'peakKb' => $peakKb,
            'changed' => count($changedFiles),
            'fingerprint' => substr(hash('xxh128', implode("\n", $changedFiles)), 0, 12),
        ];
    }

    private function buildConfig(string $cacheDirectory, string $containerCacheDirectory): string
    {
        // a modest, deterministic set: enough work to fill a cache, dry-run so nothing is written
        return sprintf(
            <<<'PHP'
                <?php

                declare(strict_types=1);

                use Rector\Config\RectorConfig;

                return RectorConfig::configure()
                    ->withPaths([%s])
                    ->withRootFiles()
                    ->withPreparedSets(deadCode: true, codeQuality: true)
                    ->withCache(%s, null, %s);
                PHP
            ,
            var_export($this->projectDirectory, true),
            var_export($cacheDirectory, true),
            var_export($containerCacheDirectory, true)
        );
    }

    /**
     * @return string[]
     */
    private function parseChangedFiles(string $jsonOutput): array
    {
        $decoded = json_decode($jsonOutput, true);
        if (! is_array($decoded) || ! isset($decoded['file_diffs']) || ! is_array($decoded['file_diffs'])) {
            return [];
        }

        $files = [];
        foreach ($decoded['file_diffs'] as $fileDiff) {
            if (is_array($fileDiff) && isset($fileDiff['file']) && is_string($fileDiff['file'])) {
                $files[] = $fileDiff['file'];
            }
        }

        sort($files);

        return $files;
    }

    private function parsePeakKb(string $timeErrorPath): int
    {
        if (! is_file($timeErrorPath)) {
            return 0;
        }

        $contents = file_get_contents($timeErrorPath);
        if (! is_string($contents)) {
            return 0;
        }

        if (preg_match('/Maximum resident set size \(kbytes\):\s*(\d+)/', $contents, $matches) === 1) {
            return (int) $matches[1];
        }

        return 0;
    }

    private function hasGnuTime(): bool
    {
        if (! is_file('/usr/bin/time')) {
            return false;
        }

        $probe = shell_exec('/usr/bin/time -v true 2>&1');

        return is_string($probe) && str_contains($probe, 'Maximum resident set size');
    }

    /**
     * @param float[] $values
     */
    private function median(array $values): float
    {
        if ($values === []) {
            return 0.0;
        }

        sort($values);
        $middle = intdiv(count($values), 2);

        if (count($values) % 2 === 1) {
            return $values[$middle];
        }

        return ($values[$middle - 1] + $values[$middle]) / 2;
    }

    /**
     * @param array{seconds: float, peakKb: int, changed: int, fingerprint: string} $current
     * @param array{seconds: float, peakKb: int, changed: int, fingerprint: string}|null $baseline
     */
    private function printPhase(string $phase, array $current, ?array $baseline): void
    {
        printf("## %s phase\n\n", $phase);

        if ($baseline === null) {
            printf(
                "wall:    %.2f s\npeak:    %s\nchanged: %d (fingerprint %s)\n\n",
                $current['seconds'],
                $this->formatMemory($current['peakKb']),
                $current['changed'],
                $current['fingerprint']
            );

            return;
        }

        $wallDelta = $this->percentDelta($baseline['seconds'], $current['seconds']);
        $memoryDelta = $this->percentDelta((float) $baseline['peakKb'], (float) $current['peakKb']);

        printf("| metric | baseline | this run | delta |\n");
        printf("| --- | --: | --: | --: |\n");
        printf("| wall | %.2f s | %.2f s | %s |\n", $baseline['seconds'], $current['seconds'], $wallDelta);
        printf("| peak | %s | %s | %s |\n", $this->formatMemory($baseline['peakKb']), $this->formatMemory($current['peakKb']), $memoryDelta);
        printf("| changed | %d | %d | %s |\n", $baseline['changed'], $current['changed'], $baseline['changed'] === $current['changed'] ? 'same' : 'DIFFERENT');

        if ($baseline['fingerprint'] !== $current['fingerprint']) {
            printf("\n*** changed-file fingerprint differs (%s vs %s) - behaviour changed, not just speed ***\n", $baseline['fingerprint'], $current['fingerprint']);
        }

        echo "\n";
    }

    private function percentDelta(float $baseline, float $current): string
    {
        if ($baseline <= 0.0) {
            return 'n/a';
        }

        $delta = ($current - $baseline) / $baseline * 100;

        return sprintf('%+.1f%% (%s)', $delta, $delta < 0 ? 'faster/less' : 'slower/more');
    }

    private function formatMemory(int $kb): string
    {
        if ($kb === 0) {
            return 'n/a';
        }

        return sprintf('%.0f MB', $kb / 1024);
    }

    private function clearDirectoryContents(string $directory): void
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
    }

    private function removeDirectory(string $directory): void
    {
        $this->clearDirectoryContents($directory);

        if (is_dir($directory)) {
            rmdir($directory);
        }
    }

    public static function resolveProject(?string $givenProject): string
    {
        if (is_string($givenProject) && $givenProject !== '') {
            $realProject = realpath($givenProject);
            if ($realProject === false || ! is_dir($realProject)) {
                fwrite(STDERR, "Project path not found: {$givenProject}\n");
                exit(1);
            }

            return $realProject;
        }

        $cacheProject = sys_get_temp_dir() . '/rector-ab-laravel';
        if (! is_dir($cacheProject)) {
            fwrite(STDOUT, "Cloning laravel/laravel (shallow) ...\n");
            $command = sprintf('git clone --depth=1 %s %s 2>&1', escapeshellarg(self::LARAVEL_REPOSITORY), escapeshellarg($cacheProject));
            $output = shell_exec($command);

            if (! is_dir($cacheProject)) {
                fwrite(STDERR, "Clone failed:\n" . (is_string($output) ? $output : '') . "\n");
                exit(1);
            }
        }

        return $cacheProject;
    }
}

// ---------------------------------------------------------------------------------------

$options = getopt('', ['project::', 'runs::', 'save::', 'baseline::', 'bin::']);

$repositoryRoot = dirname(__DIR__);

$rectorBinary = realpath((string) ($options['bin'] ?? $repositoryRoot . '/bin/rector'));
if ($rectorBinary === false) {
    fwrite(STDERR, "Rector binary not found. Pass --bin=path/to/rector\n");
    exit(1);
}

$runs = max(1, (int) ($options['runs'] ?? 3));
$projectDirectory = AbBenchmark::resolveProject(isset($options['project']) ? (string) $options['project'] : null);

$benchmark = new AbBenchmark($projectDirectory, $rectorBinary, $runs);
$result = $benchmark->run();

$baseline = null;
if (isset($options['baseline'])) {
    $baselinePath = (string) $options['baseline'];
    $baselineContents = is_file($baselinePath) ? file_get_contents($baselinePath) : false;
    if (! is_string($baselineContents)) {
        fwrite(STDERR, "Baseline file not readable: {$baselinePath}\n");
        exit(1);
    }

    $decodedBaseline = json_decode($baselineContents, true);
    $baseline = is_array($decodedBaseline) ? $decodedBaseline : null;
}

$benchmark->report($result, $baseline);

if (isset($options['save'])) {
    file_put_contents((string) $options['save'], json_encode($result, JSON_PRETTY_PRINT));
    fwrite(STDOUT, 'Saved to ' . $options['save'] . "\n");
}

exit(0);
