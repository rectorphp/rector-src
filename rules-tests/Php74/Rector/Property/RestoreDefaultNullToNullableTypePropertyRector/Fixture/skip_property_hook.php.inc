<?php

namespace Rector\Tests\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector\Fixture;

final class SkipPropertyHook
{
    public ?string $bar {
        get => 'bar';
    }
}
