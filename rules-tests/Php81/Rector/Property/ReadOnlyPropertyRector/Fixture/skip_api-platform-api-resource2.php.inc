<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 */
class SkipApiResource
{
    private int $amount;
}
