<?php

namespace Rector\Tests\Php81\Rector\ClassMethod\NewInInitializerRector\Fixture;

final class DemoClass
{

    public static function make(int $value): DemoClass
    {
        return new DemoClass($value);
    }

    public function __construct(public int $value)
    {
    }
}

final class DemoFile
{
    public function __construct(private ?DemoClass $foo = null)
    {
        // This works fine!
        $this->foo ??= DemoClass::make(100);
    }
}

final class SecondDemoFile
{
    private DemoClass $foo;

    public function __construct(?DemoClass $foo = null)
    {
        // This gets wrongly rectored
        $this->foo = $foo ?? DemoClass::make(100);
    }

}
