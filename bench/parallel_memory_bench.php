<?php

declare(strict_types=1);

/**
 * Benchmark for parallel run memory, see https://github.com/rectorphp/rector/issues/9943
 *
 * Since LPT bucket scheduling became the default, a worker lived for the whole run and was handed its
 * largest files first, so every worker grew until the end and all of them peaked at the same time.
 *
 * The change recycles a worker after MAX_CHUNKS_PER_WORKER chunks (the replacement takes over the same
 * bucket), and keeps each bucket in discovery order, so file sizes are mixed instead of largest-first.
 *
 * This bench runs a cold `rector process --dry-run` in parallel mode on a real project, and samples
 * the summed RSS of the main process and all its workers. A single process profile cannot see this:
 * the memory is spread over the worker processes.
 *
 * Run on both branches to compare:
 *   php bench/parallel_memory_bench.php /path/to/project/src [workers=8] [runs=2]
 *
 * Rector runs from the closest parent directory with vendor/autoload.php, so the project classes autoload.
 *
 * Use a large project, a worker is only recycled once it handled more than MAX_CHUNKS_PER_WORKER
 * chunks (jobSize 48 files each). Reports peak total RSS, peak single worker RSS and CPU time
 * (user+sys, workers included). The changed files fingerprint must stay identical on both branches.
 */

use Nette\Utils\FileSystem;

require __DIR__ . '/../vendor/autoload.php';

const SAMPLE_INTERVAL_MICROSECONDS = 250_000;

$pathToProcess = realpath($argv[1] ?? '');
if ($pathToProcess === false) {
    fwrite(STDERR, "Usage: php bench/parallel_memory_bench.php /path/to/project/src [workers=8] [runs=2]\n");
    exit(1);
}

$projectDirectory = $pathToProcess;
while (! is_file($projectDirectory . '/vendor/autoload.php')) {
    if (dirname($projectDirectory) === $projectDirectory) {
        fwrite(STDERR, "No vendor/autoload.php found in or above {$pathToProcess}\n");
        exit(1);
    }

    $projectDirectory = dirname($projectDirectory);
}

$workers = (int) ($argv[2] ?? 8);
$runs = (int) ($argv[3] ?? 2);

$workingDirectory = sys_get_temp_dir() . '/rector-parallel-memory-bench-' . bin2hex(random_bytes(4));
$cacheDirectory = $workingDirectory . '/cache';
$configFilePath = $workingDirectory . '/rector.php';

FileSystem::write($configFilePath, sprintf(
    <<<'PHP'
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([%s])
    ->withSkip(['*/Tests/*', '*/tests/*', '*/vendor/*', '*/Resources/*', '*/Fixture/*'])
    ->withCache(%s)
    ->withParallel(maxNumberOfProcess: %d)
    ->withPreparedSets(deadCode: true, codeQuality: true, typeDeclarations: true, earlyReturn: true);
PHP,
    var_export($pathToProcess, true),
    var_export($cacheDirectory, true),
    $workers
));

$command = sprintf(
    'exec %s %s process --dry-run --config=%s --memory-limit=2048M --output-format=json --no-progress-bar',
    escapeshellarg(PHP_BINARY),
    escapeshellarg(__DIR__ . '/../bin/rector'),
    escapeshellarg($configFilePath)
);

/**
 * @return array<int, array{int, int}> pid => [parent pid, rss in kB]
 */
$listProcesses = static function (): array {
    $processes = [];
    foreach (explode("\n", trim((string) shell_exec('ps -A -o pid=,ppid=,rss='))) as $line) {
        [$pid, $parentPid, $rss] = array_map(intval(...), preg_split('#\s+#', trim($line)));
        $processes[$pid] = [$parentPid, $rss];
    }

    return $processes;
};

$measure = static function () use ($command, $projectDirectory, $cacheDirectory, $listProcesses): array {
    FileSystem::delete($cacheDirectory);

    $cpuBefore = getrusage(1);
    $process = proc_open($command, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, $projectDirectory);
    stream_set_blocking($pipes[1], false);
    stream_set_blocking($pipes[2], false);

    $rootPid = proc_get_status($process)['pid'];
    $peakTotalRss = 0;
    $peakWorkerRss = 0;
    $output = '';

    while (proc_get_status($process)['running']) {
        $output .= stream_get_contents($pipes[1]);
        stream_get_contents($pipes[2]);

        $processes = $listProcesses();
        $childPidsByParentPid = [];
        foreach ($processes as $pid => [$parentPid]) {
            $childPidsByParentPid[$parentPid][] = $pid;
        }

        // walk the whole process tree, the workers run below the main process
        $totalRss = 0;
        $pidsToVisit = [$rootPid];
        while ($pidsToVisit !== []) {
            $pid = array_pop($pidsToVisit);
            $rss = $processes[$pid][1] ?? 0;

            $totalRss += $rss;
            if ($pid !== $rootPid) {
                $peakWorkerRss = max($peakWorkerRss, $rss);
            }

            array_push($pidsToVisit, ...($childPidsByParentPid[$pid] ?? []));
        }

        $peakTotalRss = max($peakTotalRss, $totalRss);
        usleep(SAMPLE_INTERVAL_MICROSECONDS);
    }

    $output .= stream_get_contents($pipes[1]);
    proc_close($process);
    $cpuAfter = getrusage(1);

    $cpuSeconds = static fn (array $usage): float => $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;

    $json = json_decode(substr($output, (int) strpos($output, '{')), true);
    if (! is_array($json)) {
        fwrite(STDERR, "Rector did not return JSON output:\n" . substr($output, 0, 2000) . "\n");
        exit(1);
    }

    $changedFiles = array_column($json['file_diffs'] ?? [], 'file');
    sort($changedFiles);

    return [
        'peakTotalRss' => $peakTotalRss,
        'peakWorkerRss' => $peakWorkerRss,
        'cpu' => $cpuSeconds($cpuAfter) - $cpuSeconds($cpuBefore),
        'changedFiles' => count($changedFiles),
        'fingerprint' => substr(md5(implode("\n", $changedFiles)), 0, 10),
        'errors' => count($json['errors'] ?? []),
    ];
};

printf("path:    %s\nworkers: %d\nruns:    %d\nphp:     %s\n\n", $pathToProcess, $workers, $runs, PHP_VERSION);

for ($run = 1; $run <= $runs; ++$run) {
    $result = $measure();

    printf(
        "run %d: peak total RSS %6d MiB | peak single worker %5d MiB | CPU %6.1f s | changed %d (%s) | errors %d\n",
        $run,
        intdiv($result['peakTotalRss'], 1024),
        intdiv($result['peakWorkerRss'], 1024),
        $result['cpu'],
        $result['changedFiles'],
        $result['fingerprint'],
        $result['errors']
    );
}

FileSystem::delete($workingDirectory);
