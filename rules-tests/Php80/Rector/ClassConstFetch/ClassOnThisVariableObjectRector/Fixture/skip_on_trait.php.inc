<?php

namespace Rector\Tests\Php80\Rector\ClassConstFetch\ClassOnThisVariableObjectRector\Fixture;

trait SkipOnTrait
{
    public function run()
    {
        return $this::class;
    }
}
