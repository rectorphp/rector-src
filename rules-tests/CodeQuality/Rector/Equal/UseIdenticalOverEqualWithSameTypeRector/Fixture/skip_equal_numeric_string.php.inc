<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

/**
 * @see https://3v4l.org/9XRF6
 */
final class SkipIdenticalNumericStringCompareBool
{
    public function equal()
    {
        var_dump(true == '1');
        var_dump('1' == true);
    }
}
