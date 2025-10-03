<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

final class SkipIdenticalNumericString
{
    public function equal()
    {
        var_dump(true == '1'); // true
        var_dump(true === '1'); // false
    }
}
