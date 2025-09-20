<?php

declare(strict_types=1);

namespace Rector\Testing\PHPUnit\ValueObject;

use Rector\Contract\Rector\RectorInterface;
use Rector\PostRector\Contract\Rector\PostRectorInterface;
use Rector\Util\RectorClassesSorter;
use Rector\ValueObject\ProcessResult;

/**
 * @api used in tests
 */
final readonly class RectorTestResult
{
    public function __construct(
        private string $changedContents,
        private ProcessResult $processResult
    ) {
    }

    public function getChangedContents(): string
    {
        return $this->changedContents;
    }

    /**
     * @return array<class-string<RectorInterface|PostRectorInterface>>
     */
    public function getAppliedRectorClasses(): array
    {
        $rectorClasses = [];

        foreach ($this->processResult->getFileDiffs(false) as $fileDiff) {
            $rectorClasses = array_merge($rectorClasses, $fileDiff->getRectorClasses());
        }

        return RectorClassesSorter::sort($rectorClasses);
    }
}
