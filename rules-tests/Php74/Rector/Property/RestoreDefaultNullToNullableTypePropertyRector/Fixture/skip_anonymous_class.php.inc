<?php

namespace Rector\Tests\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector\Fixture;

function someFactory()
{
    return new class {
        public ?string $name;
    };
}
