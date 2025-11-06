<?php


namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Fixture;

use Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Source\SomeInterface;
use Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Sourcet\SomeAbstractClass;

class SkipInheritance extends SomeAbstractClass
{
    public function process(): string|int|array
    {
        if (rand(0, 1)) {
            return 'text';
        }

        return 42;
    }
}

class SkipInterfaceImplementation implements SomeInterface
{
    public function getData(): string|int|bool
    {
        return 'data';
    }
}

?>
