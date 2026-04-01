<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

/**
 * @see https://3v4l.org/T4rPe#v8.5.3
 */
final class SkipWithReset
{
    public function run()
    {
        $data = [['a']];
        return array_map(reset(...), $data);
    }
}
