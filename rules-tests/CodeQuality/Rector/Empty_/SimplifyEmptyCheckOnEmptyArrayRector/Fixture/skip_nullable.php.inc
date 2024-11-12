<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector\Fixture;

final class SkipNullable
{
    /** @var array */
    public $property = [];

    public function run()
    {
        $this->property = null;
    }

    public function isEmpty(): bool
    {
        return empty($this->property);
    }
}
