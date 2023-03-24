<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\Output\Gitlab\CodeQuality;

final class Line
{
    public function __construct(
        public readonly int $begin,
        public readonly ?int $end = null,
    ) {
    }
}
