<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector\Fixture;

class SkipOnAssign
{
    public function run(?\DateTimeImmutable $param)
    {
        if (null === ($paramValue = $param)) {
            return 'no';
        }

        return $paramValue;
    }
}
