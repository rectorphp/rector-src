<?php

namespace Rector\Tests\Issues\TernaryNarrowedScopeRefresh\Fixture;

use Rector\Tests\Issues\TernaryNarrowedScopeRefresh\Source\ParentChecker;
use Rector\Tests\Issues\TernaryNarrowedScopeRefresh\Source\WiderChecker;

final class KeepArgsOfNarrowedCallInTernary
{
    public function run(ParentChecker $checker, string $value, int $attempts, bool $timedOut): bool
    {
        return $checker instanceof WiderChecker
            ? $checker->check($value, $attempts, $timedOut)
            : $checker->check($value);
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\TernaryNarrowedScopeRefresh\Fixture;

use Rector\Tests\Issues\TernaryNarrowedScopeRefresh\Source\ParentChecker;
use Rector\Tests\Issues\TernaryNarrowedScopeRefresh\Source\WiderChecker;

final class KeepArgsOfNarrowedCallInTernary
{
    public function run(ParentChecker $checker, string $value, int $attempts, bool $timedOut): bool
    {
        return $checker instanceof WiderChecker
            ? $checker->check((int) $value, $attempts, $timedOut)
            : $checker->check((int) $value);
    }
}

?>
