<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture;

class SkipElseClass
{
    public function run()
    {
        \Rector\Tests\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture\AnotherClass::someStatic();
    }
}

class AnotherClass
{
    public static function someStatic()
    {
    }
}
