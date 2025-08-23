<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\ConvertStaticToSelfRector\Fixture;

class NonFinalClassFinalMembers
{
    final public const BAR = 1;

    final public static string $publicProperty = 'test';

    final public static function publicMethod(): void
    {
    }

    public function run()
    {
        static::$publicProperty;
        static::publicMethod();
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

    final public static string $publicProperty = 'test';

    final public static function publicMethod(): void
    {
    }

    public function run()
    {
        self::$publicProperty;
        self::publicMethod();
        self::BAR;
    }
}

?>
