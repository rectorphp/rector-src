<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Source;

class ProtectedConstructClass
{
    protected function __construct($value)
    {
    }
}
