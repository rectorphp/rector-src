<?php

namespace Rector\Tests\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector\Fixture;

final class SkipChecked
{
    private ?string $name;

    public function __construct(array $items)
    {
        foreach ($items as $item) {
            $this->name = $item;
        }

        if (! isset($this->item)) {
            throw new \InvalidArgumentException();
        }
    }
}
