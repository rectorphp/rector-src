<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector\Fixture;

class SkipPossiblyUndefinedVariable
{
    public function run(bool $cond): array
    {
        if ($cond) {
            $map = ['a' => 1];
        }

        // empty() tolerates an undefined $map; `$map === []` does not.
        if (empty($map)) {
            return [];
        }

        return $map;
    }
}