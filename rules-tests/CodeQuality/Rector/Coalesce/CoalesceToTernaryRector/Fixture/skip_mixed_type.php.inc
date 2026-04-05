<?php

namespace Rector\Tests\CodeQuality\Rector\Concat\JoinStringConcatRector\Fixture;

class SkipMixedType
{
    public function run(mixed $name)
    {
        return $name ?? 'tom';
    }
}
