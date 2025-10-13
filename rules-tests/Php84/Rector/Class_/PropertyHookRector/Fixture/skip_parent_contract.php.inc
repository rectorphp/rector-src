<?php

namespace Rector\Tests\Php84\Rector\Class_\PropertyHookRector\Fixture;

use Php84\Rector\Class_\PropertyHookRector\Source\SomeParentContractInterface;

final class SkipParentContract implements SomeParentContractInterface
{
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }
}
