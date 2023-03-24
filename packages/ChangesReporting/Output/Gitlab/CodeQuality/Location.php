<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\Output\Gitlab\CodeQuality;

final class Location
{
    public function __construct(
        public readonly string $path,
        public readonly ?Line $line = null,
    ) {
    }
}
