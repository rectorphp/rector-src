<?php

namespace Rector\Tests\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector\Fixture;

use Rector\Tests\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector\Source\SomeParentClass;

final class SkipWithParentCall extends SomeParentClass
{
    protected function someMethod()
    {
        parent::someMethod();
    }
}
