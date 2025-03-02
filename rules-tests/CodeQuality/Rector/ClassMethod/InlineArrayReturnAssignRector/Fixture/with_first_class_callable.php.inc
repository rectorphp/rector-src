<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

use Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Source\SomeAssignedObject;

final class WithFirstClassCallable
{
    public function run()
    {
        $complexObjects = [];

        $complexObjects[] = new SomeAssignedObject(...);

        return $complexObjects;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

use Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Source\SomeAssignedObject;

final class WithFirstClassCallable
{
    public function run()
    {
        return [new SomeAssignedObject(...)];
    }
}

?>
