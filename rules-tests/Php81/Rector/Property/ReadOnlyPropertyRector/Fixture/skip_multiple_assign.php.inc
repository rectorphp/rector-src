<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

use Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Source\ModifiedByTrait;

final class SkipModifiedByTrait
{
    use ModifiedByTrait;

    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function execute()
    {
        $this->modify();
    }
}
