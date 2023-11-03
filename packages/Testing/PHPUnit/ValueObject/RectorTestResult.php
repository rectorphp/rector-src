<?php

declare(strict_types=1);

namespace Rector\Testing\PHPUnit\ValueObject;

use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\ValueObject\ProcessResult;

final class RectorTestResult
{
    public function __construct(
        private readonly string $changedContents,
        private readonly ProcessResult $processResult
    ) {
    }

    public function getChangedContents(): string
    {
        return $this->changedContents;
    }

    /**
     * @return array<class-string<RectorInterface>>
     */
    public function getAppliedRectorClasses(): array
    {
        $rectorClasses = [];

        foreach ($this->processResult->getFileDiffs() as $fileDiff) {
            $rectorClasses = array_merge($rectorClasses, $fileDiff->getRectorClasses());
        }

        sort($rectorClasses);

        return array_unique($rectorClasses);
    }
}
