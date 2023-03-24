<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\Output\Gitlab\CodeQuality;

final class Report
{
    public function __construct(
        public readonly string $description,
        public readonly Severity $severity,
        public readonly ?Location $location = null,
    ) {
    }
}
