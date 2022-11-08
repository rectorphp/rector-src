<?php

namespace Rector\Tests\Php80\Rector\Ternary\GetDebugTypeRector\Fixture;

class SkipClassConstFetchIfDifferentObject
{
    public function run($value, $differentObject)
    {
        return is_object($value) ? $differentObject::class : gettype($value);
    }
}
