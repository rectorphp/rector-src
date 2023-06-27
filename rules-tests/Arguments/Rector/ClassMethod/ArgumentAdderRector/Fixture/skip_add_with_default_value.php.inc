<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeClass;

class SkipAddWithDefaultValue
{
    public function skip(): void
    {
        $someClass = new SomeClass();
        $someClass->someMethod();
    }
}
