<?php

namespace Rector\Tests\CodeQuality\Rector\Concat\JoinStringConcatRector\Fixture;

class SkipNullType
{
    public function run(null $name)
    {
        return $name ?? 'tom';
    }
}
