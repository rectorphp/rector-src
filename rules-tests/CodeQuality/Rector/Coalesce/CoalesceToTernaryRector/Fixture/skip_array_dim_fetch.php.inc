<?php

namespace Rector\Tests\CodeQuality\Rector\Coalesce\CoalesceToTernaryRector\Fixture;

final class SkipArrayDimFetch
{
    public function run(array $data)
    {
        return $data['key'] ?? 'test';
    }
}
