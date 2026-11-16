<?php

declare(strict_types=1);

namespace Rector\Tests\Parallel\Application;

use PHPUnit\Framework\TestCase;
use Rector\Parallel\Application\ParallelResultCollector;
use Rector\Parallel\ValueObject\Bridge;

final class ParallelResultCollectorTest extends TestCase
{
    public function testMergesSkippedFilePathsByRuleFromWorkers(): void
    {
        $parallelResultCollector = new ParallelResultCollector();

        $parallelResultCollector->collectWorkerResult($this->createWorkerResult([
            'SomeRector' => ['/src/first.php', '/src/second.php'],
        ]));

        // a worker reports its whole cumulative state, so the same path can arrive twice
        $parallelResultCollector->collectWorkerResult($this->createWorkerResult([
            'SomeRector' => ['/src/second.php', '/src/third.php'],
            'AnotherRector' => ['/src/first.php'],
        ]));

        $processResult = $parallelResultCollector->createProcessResult();

        $this->assertSame([
            'SomeRector' => ['/src/first.php', '/src/second.php', '/src/third.php'],
            'AnotherRector' => ['/src/first.php'],
        ], $processResult->getSkippedFilePathsByRule());
    }

    /**
     * @param array<string, string[]> $skippedFilePathsByRule
     * @return array{
     *      total_changed: int,
     *      system_errors: mixed[],
     *      file_diffs: array<string, mixed>,
     *      files_count: int,
     *      system_errors_count: int,
     *      used_skips: array<string, string[]>,
     *      skipped_file_paths_by_rule: array<string, string[]>
     * }
     */
    private function createWorkerResult(array $skippedFilePathsByRule): array
    {
        return [
            Bridge::TOTAL_CHANGED => 0,
            Bridge::SYSTEM_ERRORS => [],
            Bridge::FILE_DIFFS => [],
            Bridge::FILES_COUNT => 0,
            Bridge::SYSTEM_ERRORS_COUNT => 0,
            Bridge::USED_SKIPS => [],
            Bridge::SKIPPED_FILE_PATHS_BY_RULE => $skippedFilePathsByRule,
        ];
    }
}
