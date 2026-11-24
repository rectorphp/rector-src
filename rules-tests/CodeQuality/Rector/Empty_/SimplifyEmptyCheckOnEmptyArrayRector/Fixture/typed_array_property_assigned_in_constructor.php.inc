<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector\Fixture;

final class TypedArrayPropertyAssignedInConstructor
{
    private array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector\Fixture;

final class TypedArrayPropertyAssignedInConstructor
{
    private array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }
}

?>
