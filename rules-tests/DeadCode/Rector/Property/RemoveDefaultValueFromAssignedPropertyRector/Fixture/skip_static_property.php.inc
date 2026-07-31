<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

final class SkipStaticProperty
{
    private static ?int $value = null;

    public function __construct(int $value)
    {
        self::$value = $value;
    }

    public static function getValue(): ?int
    {
        return self::$value;
    }
}
