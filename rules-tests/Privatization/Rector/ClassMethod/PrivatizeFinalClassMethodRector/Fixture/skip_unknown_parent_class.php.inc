<?php

namespace Rector\Tests\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector\Fixture;

final class SkipUnknownParentClass extends SomeUnknownParent
{
    protected function someMethod()
    {
    }
}
