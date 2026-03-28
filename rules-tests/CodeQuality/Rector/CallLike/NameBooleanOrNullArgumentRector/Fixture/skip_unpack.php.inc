<?php

namespace Rector\Tests\CodeQuality\Rector\CallLike\NameBooleanOrNullArgumentRector\Fixture;

use Rector\Tests\CodeQuality\Rector\CallLike\NameBooleanOrNullArgumentRector\Source\Service;

function (array $rest): void
{
    Service::create('value', true, ...$rest);
};
