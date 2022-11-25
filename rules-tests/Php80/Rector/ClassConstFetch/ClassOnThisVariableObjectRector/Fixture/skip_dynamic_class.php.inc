<?php

namespace Rector\Tests\Php80\Rector\ClassConstFetch\ClassOnThisVariableObjectRector\Fixture;

final class SkipDynamicClass
{
    public function run(object $object)
    {
        return $object::class;
    }
}
