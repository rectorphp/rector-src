<?php

namespace Rector\Tests\CodingStyle\Rector\If_\NullableCompareToNullRector\Fixture;

class SkipMixed
{
    public function run(mixed $mixed)
    {
        if (! $mixed) {
            return 'truethy mixed';
        }

        if ($mixed) {
            return 'falsey mixed';
        }
    }

}
