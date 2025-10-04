<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

/**
 * @see https://3v4l.org/I3GQS
 */
final class SkipBoolEqualFloat
{
    public function equal()
    {
        // maybe compare to float
        $value = rand(0, 1) ? 1.0 : null;

        var_dump(true == $value);
        var_dump($value == true);
    }
}
