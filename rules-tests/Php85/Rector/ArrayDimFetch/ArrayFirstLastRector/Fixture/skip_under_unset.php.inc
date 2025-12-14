<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector\Fixture;

final class SkipUnderUnset
{
    public function run()
    {
        $a = [1,2,3];
        unset($a[array_key_last($a)]);
    }
}
