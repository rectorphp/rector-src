<?php

namespace Rector\Tests\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector\Fixture;

final class SkipChecked
{
    private ?string $name;

    public function __construct(?string $name)
    {
        $this->name = $name;
    }
}
