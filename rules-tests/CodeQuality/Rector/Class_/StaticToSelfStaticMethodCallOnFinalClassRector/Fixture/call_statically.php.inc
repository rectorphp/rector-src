<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\StaticToSelfStaticMethodCallOnFinalClassRector\Fixture;

final class CallStatically
{
    public function execute()
    {
        $a = 1;
        static::run($a);
    }

    private static function run($a)
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
        $a = 1;
        self::run($a);
    }

    private static function run($a)
    {
    }
}

?>