<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector\Source;

abstract class SomeAbstractTestCase
{
    public static function assertSame($expected, $actual): void
    {
    }
}
