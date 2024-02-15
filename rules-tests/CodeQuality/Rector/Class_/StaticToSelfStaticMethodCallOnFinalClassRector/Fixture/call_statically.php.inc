<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\StaticToSelfStaticMethodCallOnFinalClassRector\Fixture;

final class CallStatically
{
    public function execute()
    {
        static::run();
    }

    private static function run()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\StaticToSelfStaticMethodCallOnFinalClassRector\Fixture;

final class CallStatically
{
    public function execute()
    {
        self::run();
    }

    private static function run()
    {
    }
}

?>