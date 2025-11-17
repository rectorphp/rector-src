<?php

class Foo
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

class Foo
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
