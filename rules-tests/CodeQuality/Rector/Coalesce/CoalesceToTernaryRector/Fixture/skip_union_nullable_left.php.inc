<?php

namespace Rector\Tests\CodeQuality\Rector\Concat\JoinStringConcatRector\Fixture;

class SkipUnionNullableLeft
{
    public function run(null|string $name)
    {
        return $name ?? 'tom';
    }
}
