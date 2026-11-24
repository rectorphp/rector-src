<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector\Fixture;

final class SkipUninitializedTypedArrayProperty
{
    private array $items;

    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }
}

?>
