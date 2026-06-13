<?php

namespace Rector\Tests\DeadCode\Rector\Expression\RemoveDeadStmtRector\Fixture;

final class SkipVoidCast
{
    #[\NoDiscard('this value matters')]
    public static function importantValue(): int
    {
        return 42;
    }

    public static function intentionallyDiscard(): void
    {
        // PHP 8.5: (void) explicitly discards a #[\NoDiscard] result, suppressing the warning.
        (void) self::importantValue();
    }
}
