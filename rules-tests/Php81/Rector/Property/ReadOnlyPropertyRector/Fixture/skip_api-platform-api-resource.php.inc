<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
class SkipApiResource
{
    private int $amount;
}
