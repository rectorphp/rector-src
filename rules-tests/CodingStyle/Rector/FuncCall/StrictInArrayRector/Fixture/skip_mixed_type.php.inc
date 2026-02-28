<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\StrictInArrayRector\Fixture;

class SkipMixedType
{
    public function run(mixed $value)
    {
        $items = ['10', '20', '30'];
        return in_array($value, $items);
    }
}
