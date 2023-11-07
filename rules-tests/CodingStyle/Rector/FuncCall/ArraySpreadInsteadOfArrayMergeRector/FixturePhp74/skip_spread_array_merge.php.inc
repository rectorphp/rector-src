<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp74;

use stdClass;

class SkipSpreadyArrayMerge
{
    public function run()
    {
        $values = [
            [new stdClass()],
            [new stdClass]
        ];

        $items = array_merge(...$values);

        return array_merge($items, ...$values);
    }
}
