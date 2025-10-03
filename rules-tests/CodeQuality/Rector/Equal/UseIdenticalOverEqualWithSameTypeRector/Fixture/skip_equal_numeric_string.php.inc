<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

/**
 * @see https://3v4l.org/9XRF6
 */
final class SkipIdenticalNumericStringCompareBool
{
    public function equal()
    {
        // maybe compare to numeric string
        $value = rand(0, 1) ? '1' : null;

        var_dump(true == $value);
        var_dump($value == true);
    }
}
