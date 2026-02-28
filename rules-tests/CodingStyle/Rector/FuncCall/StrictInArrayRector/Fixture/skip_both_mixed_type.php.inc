<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\StrictInArrayRector\Fixture;

class SkipBothMixedTypes
{
    /**
     * @param mixed[] $items
     */
    public function run(mixed $value, array $items)
    {
        return in_array($value, $items);
    }
}
