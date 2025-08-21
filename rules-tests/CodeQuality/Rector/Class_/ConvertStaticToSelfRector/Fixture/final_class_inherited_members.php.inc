<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\ConvertStaticToSelfRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\ConvertStaticToSelfRector\Source\BaseClass;

final class FinalClassInheritedMembers extends BaseClass
{
    public function run(): void
    {
        static::SUCCESS;
        static::$parentProperty;
        static::parentMethod();
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\ConvertStaticToSelfRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\ConvertStaticToSelfRector\Source\BaseClass;

final class FinalClassInheritedMembers extends BaseClass
{
    public function run(): void
    {
        self::SUCCESS;
        self::$parentProperty;
        self::parentMethod();
    }
}

?>
