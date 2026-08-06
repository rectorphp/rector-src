<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Source\ProtectedConstructClass;

final class SkipProtectedParentConstruct extends ProtectedConstructClass
{
    public function __construct($value)
    {
        parent::__construct($value);
    }
}
