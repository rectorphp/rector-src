<?php

namespace Rector\Tests\CodeQuality\Rector\Concat\JoinStringConcatRector\Fixture;

final class SkipArrayDimFetch
{
    public function run(array $data)
    {
        return $data['key'] ?? 'test';
    }
}
