<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InnerFunctionToPrivateMethodRector\Fixture;

class StaticClass
{
    public static function run(): void
    {
        function staticInner(int $value): int
        {
            return $value * 2;
        }

        staticInner(5);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InnerFunctionToPrivateMethodRector\Fixture;

class StaticClass
{
    public static function run(): void
    {
        self::staticInner(5);
    }
    private static function staticInner(int $value): int
    {
        return $value * 2;
    }
}

?>
