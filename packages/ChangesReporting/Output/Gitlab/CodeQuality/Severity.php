<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\Output\Gitlab\CodeQuality;

use JsonSerializable;

enum Severity: string implements JsonSerializable
{
    case INFO = 'info';
    case MAJOR = 'major';
    case MINOR = 'minor';
    case CRITICAL = 'critical';
    case BLOCKER = 'blocker';
    case UNKNOWN = 'unknown';


    /**
     * @return non-empty-string
     */
    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
