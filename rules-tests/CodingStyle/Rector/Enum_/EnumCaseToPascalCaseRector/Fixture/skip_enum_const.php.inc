<?php

namespace Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Fixture;

enum SkipEnumConst
{
    const FOO = 'foo';

    case Pending;

    function test(): void
    {
        echo self::FOO;
    }
}
