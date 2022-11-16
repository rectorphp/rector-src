<?php

namespace Rector\Core\Tests\Issues\ScopeNotAvailable\Fixture;

class FixtureForeachValue
{
    public function run()
    {
        $value = [
            [3, 1, false],
            [1, 2, false],
            [0, 0, true],
        ];

        foreach ($value as [$a_b, $b_c, $d_e]) {

        }
    }
}
