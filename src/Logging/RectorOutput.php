<?php

declare(strict_types=1);

namespace Rector\Core\Logging;

use PHPStan\Internal\BytesHelper;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\FileSystem\FilePathHelper;
use Symfony\Component\Console\Style\SymfonyStyle;

final class RectorOutput
{
    private ?float $startTime = null;

    private ?int $previousMemory = null;

    public function __construct(
        private readonly SymfonyStyle $symfonyStyle,
        private readonly FilePathHelper $filePathHelper
    ) {
    }

    public function isDebug(): bool
    {
        return $this->symfonyStyle->isDebug();
    }

    /**
     * @param class-string<RectorInterface> $rectorClass
     */
    public function printCurrentFileAndRule(string $filePath, string $rectorClass): void
    {
        $relativeFilePath = $this->filePathHelper->relativePath($filePath);

        $this->symfonyStyle->writeln('[file] ' . $relativeFilePath);
        $this->symfonyStyle->writeln('[rule] ' . $rectorClass);
    }

    public function startConsumptions(): void
    {
        $this->startTime = microtime(true);
        $this->previousMemory = memory_get_peak_usage(true);
    }

    public function printConsumptions(): void
    {
        if ($this->startTime === null || $this->previousMemory === null) {
            return;
        }

        $elapsedTime = microtime(true) - $this->startTime;
        $currentTotalMemory = memory_get_peak_usage(true);

        $previousMemory = $this->previousMemory;
        $consumed = sprintf(
            '--- consumed %s, total %s, took %.2f s',
            BytesHelper::bytes($currentTotalMemory - $previousMemory),
            BytesHelper::bytes($currentTotalMemory),
            $elapsedTime
        );
        $this->symfonyStyle->writeln($consumed);
        $this->symfonyStyle->newLine(1);
    }
}
