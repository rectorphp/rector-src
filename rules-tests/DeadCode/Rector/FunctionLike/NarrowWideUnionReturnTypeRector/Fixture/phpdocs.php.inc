<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Fixture;

use Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Source\SomeInterface;

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
    public function bar(string $class): string|int
    {
        return $class;
    }

    /** @return class-string<SomeInterface>|int */
    public function baz(string $class): string|int
    {
        return SomeInterface::class;
    }

    /** @return \Iterator<int, int>|string */
    function qux(): \Iterator|string
    {
       return new \ArrayIterator([1]);
    }

    /** @return \Iterator<int, int>|string */
    function qax(): \Iterator|string
    {
       return 'text';
    }

    /**
     * @param int $a
     */
    function quux(int $a): int|string
    {
        return $a;
    }

    /**
     * @param int $a
     * @return int|string
     */
    function mixedReturn(int $a): int|string
    {
        return $a;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Fixture;

use Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Source\SomeInterface;

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
     * @return class-string<\Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Source\SomeInterface>
     */
    public function bar(string $class): string
    {
        return $class;
    }

    /** @return class-string<\Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Source\SomeInterface> */
    public function baz(string $class): string
    {
        return SomeInterface::class;
    }

    /** @return \Iterator<int, int> */
    function qux(): \Iterator
    {
       return new \ArrayIterator([1]);
    }

    /** @return string */
    function qax(): string
    {
       return 'text';
    }

    /**
     * @param int $a
     */
    function quux(int $a): int
    {
        return $a;
    }

    /**
     * @param int $a
     * @return int
     */
    function mixedReturn(int $a): int
    {
        return $a;
    }
}

?>
