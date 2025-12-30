<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Source;

class SomeParentWithoutDefaultParam
{
    public function __construct(array $value)
    {
    }
}
