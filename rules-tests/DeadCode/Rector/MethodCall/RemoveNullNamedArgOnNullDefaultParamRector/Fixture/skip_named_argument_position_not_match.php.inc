<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullNamedArgOnNullDefaultParamRector\Fixture;

use Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullNamedArgOnNullDefaultParamRector\Source\SomeExternalClass;

final class SkipNamedArgumentPositionNotMatch
{
    public function get()
    {
        SomeExternalClass::withMiddleNotNullDefault(item: null);
    }
}
