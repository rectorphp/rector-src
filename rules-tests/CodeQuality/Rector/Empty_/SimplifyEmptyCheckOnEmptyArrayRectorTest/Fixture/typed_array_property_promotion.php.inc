<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRectorTest\Fixture;

final class TypedArrayPropertyPromotion
{
    public function __construct(private array $property)
    {
    }

    public function isEmpty(): bool
    {
        return empty($this->property);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRectorTest\Fixture;

final class TypedArrayPropertyPromotion
{
    public function __construct(private array $property)
    {
    }

    public function isEmpty(): bool
    {
        return $this->property === [];
    }
}

?>
