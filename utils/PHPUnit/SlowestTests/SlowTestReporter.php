<?php

declare(strict_types=1);

namespace Rector\Utils\PHPUnit\SlowestTests;

final class SlowTestReporter
{
    /**
     * @var int
     */
    private const LIMIT = 25;

    /**
     * Only report tests slower than this, to keep the list signal-heavy.
     * @var float
     */
    private const THRESHOLD_SECONDS = 0.5;

    /**
     * Seconds since run start, captured when a test is prepared, keyed by test id.
     * @var array<string, float>
     */
    private array $startedAt = [];

    /**
     * Test name => accumulated duration in seconds (summed across data-provider fixtures).
     * @var array<string, float>
     */
    private array $durations = [];

    public function testPrepared(string $testId, float $secondsSinceStart): void
    {
        $this->startedAt[$testId] = $secondsSinceStart;
    }

    public function testFinished(string $testId, string $testName, float $secondsSinceStart): void
    {
        if (! isset($this->startedAt[$testId])) {
            return;
        }

        $this->durations[$testName] = ($this->durations[$testName] ?? 0.0) + ($secondsSinceStart - $this->startedAt[$testId]);
        unset($this->startedAt[$testId]);
    }

    public function printSlowest(): void
    {
        $slowDurations = array_filter(
            $this->durations,
            static fn (float $seconds): bool => $seconds >= self::THRESHOLD_SECONDS
        );

        if ($slowDurations === []) {
            return;
        }

        arsort($slowDurations);
        $slowDurations = array_slice($slowDurations, 0, self::LIMIT, true);

        echo PHP_EOL . sprintf('%d slowest tests (>= %.1fs):', count($slowDurations), self::THRESHOLD_SECONDS) . PHP_EOL;

        $position = 1;
        foreach ($slowDurations as $testName => $seconds) {
            echo sprintf('%3d. %6.2fs  %s', $position, $seconds, $testName) . PHP_EOL;
            ++$position;
        }

        echo PHP_EOL;
    }
}
