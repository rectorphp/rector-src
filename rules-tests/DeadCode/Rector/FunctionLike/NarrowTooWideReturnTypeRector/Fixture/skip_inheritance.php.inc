<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

abstract class BaseClass
{
    abstract public function process(): string|int|array;
}

class SkipInheritance extends BaseClass
{
    public function process(): string|int|array
    {
        if (rand(0, 1)) {
            return 'text';
        }

        return 42;
    }
}

interface SomeInterface
{
    public function getData(): string|int|bool;
}

class SkipInterfaceImplementation implements SomeInterface
{
    public function getData(): string|int|bool
    {
        return 'data';
    }
}

?>
