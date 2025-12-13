<?php

namespace Rector\Tests\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector\Fixture;

final class SkipUnknownParentClass extends SomeUnknownParent
{
    protected const SOME_CONSTANT = 'some-value';
}
