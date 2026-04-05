<?php

namespace Rector\Tests\CodeQuality\Rector\Concat\JoinStringConcatRector\Fixture;

class SkipNullableLeft
{
    public function run(?string $name)
    {
        return $name ?? 'tom';
    }
}
