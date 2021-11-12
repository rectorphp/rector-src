<?php

namespace Rector\Tests\DowngradePhp56\Rector\CallLike\DowngradeArgumentUnpackingRector\Fixture;

final class SomeClass
{
    public static function run()
    {
        var_dump(func_get_args());
    }

    public function f1(array $items)
    {
        SomeClass::run(...$items);
    }

    public function f2(array $items)
    {
        self::run(...$items);
    }

    public function f3(array $items)
    {
        $class = 'SomeClass';
        $class::{'run'}(...$items);
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp56\Rector\CallLike\DowngradeArgumentUnpackingRector\Fixture;

final class SomeClass
{
    public static function run()
    {
        var_dump(func_get_args());
    }

    public function f1(array $items)
    {
        call_user_func_array([SomeClass::class, 'run'], $items);
    }

    public function f2(array $items)
    {
        call_user_func_array(['self', 'run'], $items);
    }

    public function f3(array $items)
    {
        $class = 'SomeClass';
        call_user_func_array([$class, 'run'], $items);
    }
}

?>
