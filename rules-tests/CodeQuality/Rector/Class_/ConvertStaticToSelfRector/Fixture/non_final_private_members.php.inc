<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\ConvertStaticToSelfRector\Fixture;

class NonFinalPrivateMembers
{
    private const BAR = 1;

    private static string $privateProperty = 'test';

    private static function privateMethod(): void
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

class NonFinalPrivateMembers
{
    private const BAR = 1;

    private static string $privateProperty = 'test';

    private static function privateMethod(): void
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
