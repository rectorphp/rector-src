<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source;

final class SomeInstance
{
    public function someMethod(): bool
    {
        return true;
    }
}
