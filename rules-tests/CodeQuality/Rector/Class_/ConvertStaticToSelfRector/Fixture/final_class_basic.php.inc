<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\ConvertStaticToSelfRector\Fixture;

final class FinalClassBasic
{
    public const SUCCESS = 'success';

    public static string $property = 'value';

    public static function run(): void
    {
    }

    public function execute()
    {
        static::run();
        static::$property;
        static::SUCCESS;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\ConvertStaticToSelfRector\Fixture;

final class FinalClassBasic
{
    public const SUCCESS = 'success';

    public static string $property = 'value';

    public static function run(): void
    {
    }

    public function execute()
    {
        self::run();
        self::$property;
        self::SUCCESS;
    }
}

?>
