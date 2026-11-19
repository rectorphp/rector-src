<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\TernaryNarrowedScopeRefresh\Source;

class ParentChecker
{
    public function check(mixed $value): bool
    {
        return true;
    }
}
