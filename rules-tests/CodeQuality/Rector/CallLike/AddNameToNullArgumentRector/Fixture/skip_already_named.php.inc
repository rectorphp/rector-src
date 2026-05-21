<?php

namespace Rector\Tests\CodeQuality\Rector\CallLike\AddNameToNullArgumentRector\Fixture;

use Rector\Tests\CodeQuality\Rector\CallLike\AddNameToNullArgumentRector\Source\Service;

function (Service $service, string $value): void
{
    $service->configure($value, default: null);
};
