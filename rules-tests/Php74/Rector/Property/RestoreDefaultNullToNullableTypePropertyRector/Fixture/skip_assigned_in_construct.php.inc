<?php

namespace Rector\Tests\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector\Fixture;

final class SkipAssignedInConstruct
{
    public ?string $name;

    public function __construct(?string $name = null)
    {
        $this->name = $name;
    }
}
