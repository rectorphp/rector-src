<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\StrictInArrayRector\Fixture;

class SkipDocblockArray
{
    /**
     * @param string[] $items
     */
    public function run(string $value, array $items)
    {
        return in_array($value, $items);
    }
}
