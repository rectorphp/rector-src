<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAnyCombinedRector\Fixture;

class SkipNonArray
{
    public function checkAnimal(\ArrayIterator $animals)
    {
        foreach ($animals as $animal) {
            if (str_starts_with($animal, 'c')) {
                return true;
            }
        }
        return false;
    }
}
