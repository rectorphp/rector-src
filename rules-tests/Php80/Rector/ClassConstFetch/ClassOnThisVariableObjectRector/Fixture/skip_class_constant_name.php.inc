<?php

namespace Rector\Tests\Php80\Rector\ClassConstFetch\ClassOnThisVariableObjectRector\Fixture;

final class SkipClassConstantName
{
    private const SOME_CONSTANT = 'test';
    public function run()
    {
        return $this::SOME_CONSTANT;
    }
}
