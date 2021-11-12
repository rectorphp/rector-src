<?php

namespace Rector\Tests\DowngradePhp56\Rector\CallLike\DowngradeArgumentUnpackingRector\Fixture;

final class SomeClass
{
    public function run()
    {
        var_dump(func_get_args());
    }

    public function chain()
    {
        return $this;
    }

    public function f1(array $items)
    {
        $this->run(...$items);
    }

    public function f2(array $items)
    {
        $method = 'run';
        $this->$method(...$items);
    }

    public function f3(array $items)
    {
        $this->chain()->run('foo', ...$items);
    }

    public function f4(array $items)
    {
        $this->chain(...$items)->run(...$items);
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp56\Rector\CallLike\DowngradeArgumentUnpackingRector\Fixture;

final class SomeClass
{
    public function run()
    {
        var_dump(func_get_args());
    }

    public function chain()
    {
        return $this;
    }

    public function f1(array $items)
    {
        call_user_func_array([$this, 'run'], $items);
    }

    public function f2(array $items)
    {
        $method = 'run';
        call_user_func_array([$this, $method], $items);
    }

    public function f3(array $items)
    {
        call_user_func_array([$this->chain(), 'run'], array_merge(['foo'], $items));
    }

    public function f4(array $items)
    {
        call_user_func_array([call_user_func_array([$this, 'chain'], $items), 'run'], $items);
    }
}

?>
