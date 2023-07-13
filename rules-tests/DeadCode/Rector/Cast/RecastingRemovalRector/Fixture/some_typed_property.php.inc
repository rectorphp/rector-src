<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Fixture;

use Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Source\ExternalStrictTypedProperty;

final class SomeTypedProperty
{
    public function run(ExternalStrictTypedProperty $externalStrictTypedProperty)
    {
        $value = (int) $externalStrictTypedProperty->property;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Fixture;

use Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Source\ExternalStrictTypedProperty;

final class SomeTypedProperty
{
    public function run(ExternalStrictTypedProperty $externalStrictTypedProperty)
    {
        $value = $externalStrictTypedProperty->property;
    }
}

?>
