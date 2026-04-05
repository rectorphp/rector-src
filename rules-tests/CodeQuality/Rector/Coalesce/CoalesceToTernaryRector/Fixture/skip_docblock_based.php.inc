<?php

namespace Rector\Tests\CodeQuality\Rector\Concat\JoinStringConcatRector\Fixture;

class SkipDocblockBased
{
    /**
     * @param string $name
     */
    public function run($name)
    {
        return $name ?? 'tom';
    }
}