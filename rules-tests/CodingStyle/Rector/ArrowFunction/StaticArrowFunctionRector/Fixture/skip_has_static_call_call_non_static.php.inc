<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector\Fixture;

/**
 * @see https://3v4l.org/rQfZd
 */
class SkipHasStaticCallCallNonStatic
{
    public function bar()
    {
        return 'test';
    }

    public function foobar()
    {
        return fn() => self::bar();
    }
}
