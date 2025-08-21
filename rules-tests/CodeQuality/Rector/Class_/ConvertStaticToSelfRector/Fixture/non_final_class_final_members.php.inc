<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\ConvertStaticToSelfRector\Fixture;

class NonFinalClassFinalMembers
{
    final public const BAR = 1;

    final public static string $privateProperty = 'test';

    final public static function privateMethod(): void
    {
    }

    public function run()
    {
        static::$privateProperty;
        static::privateMethod();
        static::BAR;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\ConvertStaticToSelfRector\Fixture;

class NonFinalClassFinalMembers
{
    final public const BAR = 1;

    final public static string $privateProperty = 'test';

    final public static function privateMethod(): void
    {
    }

    public function run()
    {
        self::$privateProperty;
        self::privateMethod();
        self::BAR;
    }
}

?>
