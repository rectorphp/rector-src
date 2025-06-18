<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\TernaryImplodeToImplodeRector\Fixture;

class SkipDifferentVariable
{
    public function run(array $values)
    {
        return $values === [] ? '' : implode('...', $values + ['___']);
    }
}
