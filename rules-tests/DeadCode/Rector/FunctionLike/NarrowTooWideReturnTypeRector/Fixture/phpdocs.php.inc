<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

use Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Source\SomeInterface;

final class PhpDocs
{
    /** @return string|int|float */
    public function foo(): string|int|float
    {
        if (rand(0, 1)) {
            return 'text';
        }

        return 1000;
    }

    /**
     * @param class-string<SomeInterface> $class
     * @return class-string<SomeInterface>|int
     */
    public function bar($class): string|int
    {
        return $class;
    }

    /** @return class-string<SomeInterface>|int */
    public function baz($class): string|int
    {
        return SomeInterface::class;
    }

    /** @return \Iterator<int, int>|string */
    function qux(): \Iterator|string
    {
       return new \ArrayIterator([1]);
    }

    /**
     * @param int $a
     */
    function quux($a): int|string
    {
        return $a;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

use Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Source\SomeInterface;

final class PhpDocs
{
    /** @return int|string */
    public function foo(): string|int
    {
        if (rand(0, 1)) {
            return 'text';
        }

        return 1000;
    }

    /**
     * @param class-string<SomeInterface> $class
     * @return class-string<Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Source\SomeInterface>
     */
    public function bar($class): string
    {
        return $class;
    }

    /** @return class-string<Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Source\SomeInterface> */
    public function baz($class): string
    {
        return SomeInterface::class;
    }

    /** @return \Iterator<int, int> */
    function qux(): \Iterator
    {
       return new \ArrayIterator([1]);
    }

    /**
     * @param int $a
     */
    function quux($a): int
    {
        return $a;
    }
}

?>
