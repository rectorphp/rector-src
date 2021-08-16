<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\DateTimeToDateTimeInterfaceRector\Fixture;

class SkipStringType
{
    public static function run(string $param)
    {
    }

    public static function run2(?string $param)
    {
    }
}
