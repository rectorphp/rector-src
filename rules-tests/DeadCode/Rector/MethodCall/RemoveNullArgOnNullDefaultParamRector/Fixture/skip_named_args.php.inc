<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Fixture;

function skipNamedArgs(?string $someClass = null)
{
}

skipNamedArgs(someClass: null);
