<?php

namespace Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Fixture;

use \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector;

final class StaticMethodCallToStaticProperty
{
    public function run($value)
    {
        $archived = \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::archived();
        $published = SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::published();
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Fixture;

use \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector;

final class StaticMethodCallToStaticProperty
{
    public function run($value)
    {
        $archived = \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::ARCHIVED;
        $published = \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::PUBLISHED;
    }
}

?>
