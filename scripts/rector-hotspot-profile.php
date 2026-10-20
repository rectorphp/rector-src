<?php

declare(strict_types=1);

/**
 * Single-process hot-spot profiler for Rector.
 *
 * Step 1 of a perf investigation: before guessing at a cache or an algorithm change, find
 * where the time and the call volume actually go. This runs a real `rector process --dry-run`
 * over a real project in ONE process (parallel disabled - parallel workers each write their own
 * cachegrind and make the numbers useless), under the Xdebug profiler, then parses the
 * cachegrind and ranks functions.
 *
 * It reports two tables, both filtered to a namespace (default Rector\):
 *   - by CALL COUNT  -> memoization candidates: a function called many times, often on repeating
 *                       inputs, is where a cache might pay. Confirm the repeat rate separately
 *                       before adding one (unique inputs => a cache is pure overhead).
 *   - by SELF COST   -> where CPU actually burns (self time, excluding callees).
 *
 * Requirements: the xdebug extension (profiler). The run is ~10-20x slower than normal under the
 * profiler.
 *
 * Interpreting the output - two traps:
 *   - Use a LARGE real project. On a handful of files, container bootstrap (LazyContainerFactory,
 *     RectorConfig::rule, service __construct) dominates both tables and buries the per-node work.
 *     Point --project at a real application (hundreds of files) so processing outweighs boot.
 *   - Most type/scope cost lives in PHPStan\* internals, not Rector\*. The default filter keeps
 *     only Rector\, which shows the thin wrappers; pass --filter='^(Rector|PHPStan)' to also see
 *     where the underlying reflection/type work burns.
 *
 * The call-count table lists memoization candidates, but a high count alone is not enough: confirm
 * the inputs actually REPEAT (same node/class/version resolved again) before adding a cache -
 * unique inputs make a cache pure overhead.
 *
 * Usage:
 *   php scripts/rector-hotspot-profile.php --project=/path/to/a/real/app
 *   php scripts/rector-hotspot-profile.php --project=/path/to/app --top=40 --filter='^(Rector|PHPStan)'
 *
 * Options:
 *   --project=PATH   directory to analyse (required; a real app, not a few files)
 *   --top=N          rows per table (default 30)
 *   --filter=REGEX   PCRE (without delimiters) matched against the function name (default '^Rector\\')
 *   --bin=PATH       rector binary (default: bin/rector in this checkout)
 *   --keep           keep the cachegrind file instead of deleting it (path is printed)
 */
final readonly class HotspotProfiler
{
    public function __construct(
        private string $projectDirectory,
        private string $rectorBinary,
        private int $top,
        private string $filterRegex,
        private bool $keepProfile,
    ) {
    }

    public function run(): void
    {
        $workingDirectory = sys_get_temp_dir() . '/rector-hotspot-' . bin2hex(random_bytes(4));
        $cacheDirectory = $workingDirectory . '/cache';
        $containerCacheDirectory = $workingDirectory . '/container';
        $profileDirectory = $workingDirectory . '/profile';

        foreach ([$cacheDirectory, $containerCacheDirectory, $profileDirectory] as $directory) {
            if (! mkdir($directory, 0o777, true)) {
                throw new RuntimeException('Could not create directory ' . $directory);
            }
        }

        $configPath = $workingDirectory . '/rector-hotspot-config.php';
        file_put_contents($configPath, $this->buildConfig($cacheDirectory, $containerCacheDirectory));

        printf(
            "project:  %s\nbinary:   %s\nphp:      %s\nxdebug:   %s\n\n",
            $this->projectDirectory,
            $this->rectorBinary,
            PHP_VERSION,
            phpversion('xdebug') ?: 'MISSING'
        );

        echo "profiling single-process run (dry-run, parallel off) ...\n";
        $cachegrindFile = $this->profileRun($configPath, $profileDirectory);
        echo 'parsing ' . $cachegrindFile . " ...\n\n";

        [$selfCosts, $callCounts] = $this->parseCachegrind($cachegrindFile);

        $this->printTable('TOP BY CALL COUNT (memoization candidates)', $callCounts, 'calls');
        echo "\n";
        $this->printTable('TOP BY SELF COST (where CPU burns)', $selfCosts, 'self');

        if ($this->keepProfile) {
            $kept = getcwd() . '/rector-hotspot.cachegrind';
            copy($cachegrindFile, $kept);
            echo "\nprofile kept at: " . $kept . "\n";
        }

        $this->removeDirectory($workingDirectory);
    }

    private function profileRun(string $configPath, string $profileDirectory): string
    {
        // --xdebug is essential: without it Rector's XdebugHandler restarts the process without
        // xdebug before the container boots, so the profile would only cover the thin pre-restart
        // launcher. With it the restart is skipped and xdebug stays live for the real run, which
        // --withoutParallel() (see buildConfig) keeps in this same process - no worker child.
        $rectorCommand = sprintf(
            '%s -d %s -d %s -d %s -d %s %s process --dry-run --no-progress-bar --xdebug --output-format=json --config=%s',
            escapeshellarg(PHP_BINARY),
            escapeshellarg('xdebug.mode=profile'),
            escapeshellarg('xdebug.start_with_request=yes'),
            escapeshellarg('xdebug.output_dir=' . $profileDirectory),
            escapeshellarg('xdebug.use_compression=0'),
            escapeshellarg($this->rectorBinary),
            escapeshellarg($configPath)
        );

        // XDEBUG_MODE in the environment overrides -d, so set it too
        shell_exec('XDEBUG_MODE=profile ' . $rectorCommand . ' 2>/dev/null');

        $profiles = glob($profileDirectory . '/cachegrind.out.*') ?: [];
        if ($profiles === []) {
            throw new RuntimeException(
                'No cachegrind file produced. Is xdebug installed and is the project path valid?'
            );
        }

        // single process => exactly one file; if several, take the largest (the main run)
        usort($profiles, static fn (string $a, string $b): int => filesize($b) <=> filesize($a));

        return $profiles[0];
    }

    /**
     * Parse a cachegrind file into [selfCostByFunction, callCountByFunction].
     *
     * Format recap: each block starts with `fl=` then `fn=<name>`, followed by `<line> <cost>`
     * self-cost lines, and `cfn=<callee>` + `calls=<n> ...` + one cost line per call edge. We sum
     * the self-cost lines for the current fn, and attribute each `calls=<n>` to the preceding cfn.
     *
     * @return array{array<string, int>, array<string, int>}
     */
    private function parseCachegrind(string $cachegrindFile): array
    {
        $handle = fopen($cachegrindFile, 'r');
        if ($handle === false) {
            throw new RuntimeException('Could not open ' . $cachegrindFile);
        }

        $selfCosts = [];
        $callCounts = [];

        // xdebug writes compressed names: first time "(123) Full\Name", later just "(123)".
        $names = [];
        $resolveName = static function (string $raw) use (&$names): string {
            if (preg_match('/^\((\d+)\)(?:\s+(.*))?$/', $raw, $match) !== 1) {
                return $raw;
            }

            $id = $match[1];
            if (isset($match[2]) && $match[2] !== '') {
                $names[$id] = $match[2];
            }

            return $names[$id] ?? ('(#' . $id . ')');
        };

        $currentFunction = null;
        $calleeFunction = null;

        while (($line = fgets($handle)) !== false) {
            $line = rtrim($line, "\r\n");
            if ($line === '') {
                continue;
            }

            if (str_starts_with($line, 'fn=')) {
                $currentFunction = $resolveName(substr($line, 3));
                $calleeFunction = null;
                $selfCosts[$currentFunction] ??= 0;
                continue;
            }

            if (str_starts_with($line, 'cfn=')) {
                $calleeFunction = $resolveName(substr($line, 4));
                continue;
            }

            if (str_starts_with($line, 'calls=')) {
                if ($calleeFunction !== null) {
                    $parts = explode(' ', substr($line, 6));
                    $callCounts[$calleeFunction] = ($callCounts[$calleeFunction] ?? 0) + (int) $parts[0];
                }

                continue;
            }

            // a cost line is "<position> <cost> [<cost>...]"; the first cost column is Time.
            // It belongs to the current fn as self cost, unless it follows a cfn (then it is the
            // call edge's inclusive cost, attributed to the callee, not self).
            if ($currentFunction !== null && preg_match('/^\d+\s+(\d+)/', $line, $match) === 1) {
                if ($calleeFunction === null) {
                    $selfCosts[$currentFunction] += (int) $match[1];
                } else {
                    // consume the call-edge cost line; reset so later self lines count again
                    $calleeFunction = null;
                }
            }
        }

        fclose($handle);

        return [$selfCosts, $callCounts];
    }

    /**
     * @param array<string, int> $costByFunction
     */
    private function printTable(string $title, array $costByFunction, string $unit): void
    {
        $filtered = [];
        foreach ($costByFunction as $function => $cost) {
            if (preg_match('#' . $this->filterRegex . '#', $function) === 1) {
                $filtered[$function] = $cost;
            }
        }

        arsort($filtered);
        $rows = array_slice($filtered, 0, $this->top, true);

        printf("== %s ==\n", $title);
        printf("%14s  %s\n", $unit, 'function');
        foreach ($rows as $function => $cost) {
            printf("%14s  %s\n", number_format($cost), $function);
        }
    }

    private function buildConfig(string $cacheDirectory, string $containerCacheDirectory): string
    {
        return sprintf(
            <<<'PHP'
                <?php

                declare(strict_types=1);

                use Rector\Config\RectorConfig;

                return RectorConfig::configure()
                    ->withPaths([%s])
                    ->withoutParallel()
                    ->withPreparedSets(deadCode: true, codeQuality: true, typeDeclarations: true)
                    ->withCache(%s, null, %s);
                PHP
            ,
            var_export($this->projectDirectory, true),
            var_export($cacheDirectory, true),
            var_export($containerCacheDirectory, true)
        );
    }

    private function removeDirectory(string $directory): void
    {
        if (! is_dir($directory)) {
            return;
        }

        $items = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($items as $item) {
            /** @var SplFileInfo $item */
            $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
        }

        rmdir($directory);
    }
}

// standalone: drives the rector binary as a subprocess and parses its cachegrind, so it needs
// no autoloader of its own - it runs with plain php, independent of this checkout's vendor/

/**
 * @param string[] $argv
 */
function option(array $argv, string $name, ?string $default): ?string
{
    foreach ($argv as $arg) {
        if (str_starts_with($arg, '--' . $name . '=')) {
            return substr($arg, strlen($name) + 3);
        }

        if ($arg === '--' . $name) {
            return '1';
        }
    }

    return $default;
}

$projectDirectory = option($argv, 'project', null);
if ($projectDirectory === null || ! is_dir($projectDirectory)) {
    fwrite(STDERR, "--project=PATH is required and must be a directory\n");
    exit(1);
}

if (phpversion('xdebug') === false) {
    fwrite(STDERR, "xdebug extension is required for profiling\n");
    exit(1);
}

$profiler = new HotspotProfiler(
    rtrim($projectDirectory, '/'),
    (string) option($argv, 'bin', __DIR__ . '/../bin/rector'),
    (int) option($argv, 'top', '30'),
    (string) option($argv, 'filter', '^Rector\\\\'),
    option($argv, 'keep', null) !== null,
);

$profiler->run();
