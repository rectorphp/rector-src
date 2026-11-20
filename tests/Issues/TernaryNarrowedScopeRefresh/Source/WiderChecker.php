<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\TernaryNarrowedScopeRefresh\Source;

final class WiderChecker extends ParentChecker
{
    public function check(mixed $value, int $attempts = 0, bool $timedOut = false): bool
    {
        return true;
    }
}
