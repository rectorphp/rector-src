<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\TernaryImplodeToImplodeRector\Fixture;

class SkipFirstClassCallable
{
    public function run(array $values)
    {
        return $values === [] ? '' : implode(...);
    }
}
