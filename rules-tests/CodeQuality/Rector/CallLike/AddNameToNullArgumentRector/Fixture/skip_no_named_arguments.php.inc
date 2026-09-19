<?php

namespace Rector\Tests\CodeQuality\Rector\CallLike\AddNameToNullArgumentRector\Fixture;

use Rector\Tests\CodeQuality\Rector\CallLike\AddNameToNullArgumentRector\Source\NoNamedArgumentsService;

function (NoNamedArgumentsService $service, string $value): void
{
    $service->configure($value, null);
    NoNamedArgumentsService::create($value, null);
    new NoNamedArgumentsService($value, null);
};
