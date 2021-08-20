<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\DateTimeToDateTimeInterfaceRector\Fixture;

use DateTime as PhpDateTime;

class FunctionDateTime extends PhpDateTime
{
    public function getDateTimeCustomFormat(): ?string
    {
        return $this->format('Y-m-d H:i:s');
    }
}

class SkipChildClass
{
    public static function bar(?FunctionDateTime $datetime)
    {
        // ...
    }

    public static function baz(FunctionDateTime $datetime)
    {
        // ...
    }
}
