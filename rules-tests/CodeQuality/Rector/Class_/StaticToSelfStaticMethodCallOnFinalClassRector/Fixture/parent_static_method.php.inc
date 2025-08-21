<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\StaticToSelfStaticMethodCallOnFinalClassRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\StaticToSelfStaticMethodCallOnFinalClassRector\Source\BaseClass;

final class ParentStaticMethod extends BaseClass
{
    public function test(): string
    {
        return static::parentMethod();
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\StaticToSelfStaticMethodCallOnFinalClassRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\StaticToSelfStaticMethodCallOnFinalClassRector\Source\BaseClass;

final class ParentStaticMethod extends BaseClass
{
    public function test(): string
    {
        return self::parentMethod();
    }
}

?>
