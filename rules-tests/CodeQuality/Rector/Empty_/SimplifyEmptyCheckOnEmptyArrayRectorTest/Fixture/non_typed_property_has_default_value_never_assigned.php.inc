<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRectorTest\Fixture;

final class NonTypedPropertyHasDefaultValueNeverAssigned
{
    /** @var array */
    public $property = [];

    public function isEmpty(): bool
    {
        return empty($this->property);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRectorTest\Fixture;

final class NonTypedPropertyHasDefaultValueNeverAssigned
{
    /** @var array */
    public $property = [];

    public function isEmpty(): bool
    {
        return $this->property === [];
    }
}

?>