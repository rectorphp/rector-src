<?php

namespace Rector\Tests\CodeQuality\Rector\CallLike\AddNameToBooleanArgumentRector\Fixture;

use Rector\Tests\CodeQuality\Rector\CallLike\AddNameToBooleanArgumentRector\Source\Service;

function (array $rest): void
{
    Service::create('value', true, ...$rest);
};
