<?php

namespace Rector\Tests\DowngradePhp56\Rector\CallLike\DowngradeArgumentUnpackingRector\Fixture;

final class SomeClass extends stdClass
{
    public function f1(array $items)
    {
        return new SomeClass(...$items);
    }

    public function f2(array $items)
    {
        return new self(...$items, 'foo');
    }

    public function f3(array $items)
    {
        return new static(...$items);
    }

    public function f4(array $items)
    {
        return new parent(...$items);
    }

    public function f5(mixed $item)
    {
        return new SomeClass(...['foo', $item]);
    }

    public function f6(array $items)
    {
        $class = 'SomeClass';
        return new $class(...$items);
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp56\Rector\CallLike\DowngradeArgumentUnpackingRector\Fixture;

final class SomeClass extends stdClass
{
    public function f1(array $items)
    {
        return (new \ReflectionClass(SomeClass::class))->newInstanceArgs($items);
    }

    public function f2(array $items)
    {
        return (new \ReflectionClass(get_class()))->newInstanceArgs(array_merge($items, ['foo']));
    }

    public function f3(array $items)
    {
        return (new \ReflectionClass(get_called_class()))->newInstanceArgs($items);
    }

    public function f4(array $items)
    {
        return (new \ReflectionClass(get_parent_class()))->newInstanceArgs($items);
    }

    public function f5(mixed $item)
    {
        return new SomeClass('foo', $item);
    }

    public function f6(array $items)
    {
        $class = 'SomeClass';
        return (new \ReflectionClass($class))->newInstanceArgs($items);
    }
}

?>
