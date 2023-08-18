<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

use Illuminate\Support\Collection;

final class SkipCollectionOfType
{
    private Collection $items;

    public function __construct(Collection $items)
    {
        $this->items = $items;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }
}
