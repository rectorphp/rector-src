<?php

namespace Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Fixture;

use Illuminate\Support\Collection;

class SkipLaravelClosureBinding
{
    /**
     * @return void
     */
    public function registerCollectionMacros(): void
    {
        Collection::macro('ksort', function (): Collection {
            // macros callbacks are bound to collection so we can safely access
            // protected Collection::items
            $list = $this->items;
            ksort($list);

            return new static($list);
        });
    }
}
