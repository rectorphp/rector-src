<?php

namespace Rector\Tests\Php80\Rector\Ternary\GetDebugTypeRector\Fixture;

class SkipClassConstFetchIfDifferentName
{
    public function run($value)
    {
        return is_object($value) ? $value::SOME_CONSTANT : gettype($value);
    }
}
