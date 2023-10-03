<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Fixture;

use Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Source\ExternalStrictReturnType;

class MethodCallReturnStrictTypeExternal
{
    public function run(): string
    {
        $obj = new ExternalStrictReturnType();
        return (string) $obj->getResult();
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Fixture;

use Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Source\ExternalStrictReturnType;

class MethodCallReturnStrictTypeExternal
{
    public function run(): string
    {
        $obj = new ExternalStrictReturnType();
        return $obj->getResult();
    }
}

?>
