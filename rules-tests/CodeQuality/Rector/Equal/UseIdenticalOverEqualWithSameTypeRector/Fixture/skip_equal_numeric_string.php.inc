<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

final class SkipIdenticalNumericString
{
    public function equal()
    {
        $a = 1;
        var_dump($a == '1');
        var_dump($a === '1');
    }
}
