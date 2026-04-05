<?php

namespace Rector\Tests\CodeQuality\Rector\Concat\JoinStringConcatRector\Fixture;

class SkipNotDefined
{
    public function run()
    {
        return $name ?? 'tom';
    }
}