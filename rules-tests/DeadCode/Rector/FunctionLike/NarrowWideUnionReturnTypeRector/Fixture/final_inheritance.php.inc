<?php


namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Fixture;

use Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Source\SomeInterface;
use Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Sourcet\SomeAbstractClass;

final class FinalInheritance extends SomeAbstractClass
{
    public function process(): string|int|array
    {
        if (rand(0, 1)) {
            return 'text';
        }

        return 42;
    }
}

final class FinalInterfaceImplementation implements SomeInterface
{
    public function getData(): string|int|bool
    {
        return 'data';
    }
}

?>
-----
<?php


namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Fixture;

use Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Source\SomeInterface;
use Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Sourcet\SomeAbstractClass;

final class FinalInheritance extends SomeAbstractClass
{
    public function process(): string|int
    {
        if (rand(0, 1)) {
            return 'text';
        }

        return 42;
    }
}

final class FinalInterfaceImplementation implements SomeInterface
{
    public function getData(): string
    {
        return 'data';
    }
}

?>
