<?php

namespace Rector\Tests\DowngradePhp56\Rector\CallLike\DowngradeArgumentUnpackingRector\Fixture;

final class SomeClass
{
    public function f1(array $items)
    {
        some_function(...$items);
    }

    public function f2(array $items)
    {
        some_function('foo', ...$items, 'bar');
    }

    public function f3(mixed $item)
    {
        some_function('foo', ...[$item, 'baz'], 'bar', 'qux');
    }

    public function f4(callable $callback, array $items)
    {
        return $callback('foo', 'bar', ...$items);
    }

    public function f5(array $items, array $otherItems)
    {
        some_function(...$items, ...$otherItems);
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp56\Rector\CallLike\DowngradeArgumentUnpackingRector\Fixture;

final class SomeClass
{
    public function f1(array $items)
    {
        call_user_func_array('some_function', $items);
    }

    public function f2(array $items)
    {
        call_user_func_array('some_function', array_merge(['foo'], $items, ['bar']));
    }

    public function f3(mixed $item)
    {
        call_user_func_array('some_function', ['foo', $item, 'baz', 'bar', 'qux']);
    }

    public function f4(callable $callback, array $items)
    {
        return call_user_func_array($callback, array_merge(['foo', 'bar'], $items));
    }

    public function f5(array $items, array $otherItems)
    {
        call_user_func_array('some_function', array_merge($items, $otherItems));
    }
}

?>
