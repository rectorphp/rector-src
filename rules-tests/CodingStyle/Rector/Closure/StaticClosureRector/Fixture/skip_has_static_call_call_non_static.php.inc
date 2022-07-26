<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\StaticClosureRector\Fixture;

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
        return function() { return self::bar(); };
    }
}
