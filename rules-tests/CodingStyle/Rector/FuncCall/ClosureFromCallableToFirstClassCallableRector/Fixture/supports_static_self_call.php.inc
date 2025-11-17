<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ClosureFromCallableToFirstClassCallableRector\Fixture;

class SupportStaticSelfCall
{
    public static function bar(): void
    {
        set_exception_handler(Closure::fromCallable([self::class, 'baz']));
    }

    public static function baz(): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ClosureFromCallableToFirstClassCallableRector\Fixture;

class SupportStaticSelfCall
{
    public static function bar(): void
    {
        set_exception_handler(self::baz(...));
    }

    public static function baz(): void
    {
    }
}

?>
