<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector\Source\OtherClass;

final class PropertyFetchFromOtherObject
{
    public function isEmptyArray(OtherClass $otherClass): bool
    {
        return empty($otherClass->prop);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector\Source\OtherClass;

final class PropertyFetchFromOtherObject
{
    public function isEmptyArray(OtherClass $otherClass): bool
    {
        return $otherClass->prop === [];
    }
}

?>
