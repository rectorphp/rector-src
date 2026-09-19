<?php

namespace Rector\Tests\CodeQuality\Rector\CallLike\AddNameToBooleanArgumentRector\Fixture;

use Rector\Tests\CodeQuality\Rector\CallLike\AddNameToBooleanArgumentRector\Source\NoNamedArgumentsService;

function (NoNamedArgumentsService $service, string $value): void
{
    $service->configure($value, true);
    NoNamedArgumentsService::create($value, true);
    new NoNamedArgumentsService($value, true);
};
