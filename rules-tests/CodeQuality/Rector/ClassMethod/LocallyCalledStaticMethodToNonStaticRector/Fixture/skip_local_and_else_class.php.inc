<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture;

class SkipLocalAndElseClass
{
    public function run()
    {
        \Rector\Tests\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture\YetAnotherClass::someStatic();
    }
}

class YetAnotherClass
{
    public static function someStatic()
    {
        self::callHere();
    }

    public function callHere()
    {
    }
}
