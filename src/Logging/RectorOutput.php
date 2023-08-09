<?php

declare(strict_types=1);

namespace Rector\Core\Logging;

use Rector\Core\FileSystem\FilePathHelper;
use Symfony\Component\Console\Style\SymfonyStyle;
use PHPStan\Internal\BytesHelper;

final class RectorOutput
{
    public function __construct(
        private readonly SymfonyStyle $symfonyStyle,
        private readonly FilePathHelper $filePathHelper
    )
    {
    }

    public function isDebug(): bool
    {
        return $this->symfonyStyle->isDebug();
    }

    public function printCurrentFileAndRule(string $filePath): void
    {
        $relativeFilePath = $this->filePathHelper->relativePath($filePath);

        $this->symfonyStyle->writeln('[file] ' . $relativeFilePath);
        $this->symfonyStyle->writeln('[rule] ' . static::class);
    }

    public function printConsumptions(float $startTime, int $previousMemory): void
    {
        $elapsedTime = microtime(true) - $startTime;
        $currentTotalMemory = memory_get_peak_usage(true);

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
