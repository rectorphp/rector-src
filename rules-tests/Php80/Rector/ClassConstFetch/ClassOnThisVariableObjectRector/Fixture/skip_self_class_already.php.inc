<?php

namespace Rector\Tests\Php80\Rector\ClassConstFetch\ClassOnThisVariableObjectRector\Fixture;

final class SkipSelfClassAlready
{
    public function run()
    {
        return self::class;
    }
}
