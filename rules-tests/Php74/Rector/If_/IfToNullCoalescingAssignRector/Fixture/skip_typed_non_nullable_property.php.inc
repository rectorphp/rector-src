<?php

namespace Rector\Tests\Php74\Rector\If_\IfToNullCoalescingAssignRector\Fixture;

class SkipTypedNonNullableProperty
{
    private static array $parameters;

    public function get(): array
    {
        if (! isset(self::$parameters)) {
            self::$parameters = ['default'];
        }

        return self::$parameters;
    }
}
