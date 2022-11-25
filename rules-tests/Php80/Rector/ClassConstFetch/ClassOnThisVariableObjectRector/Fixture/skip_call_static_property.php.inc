<?php

namespace Rector\Tests\Php80\Rector\ClassConstFetch\ClassOnThisVariableObjectRector\Fixture;

final class SkipCallStaticProperty
{
    public static $bar = 'test';
    public function run()
    {
        return $this::$bar;
    }
}
