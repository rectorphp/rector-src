<?php

namespace Rector\Tests\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector\Fixture;

final class SkipAlreadyPrivate
{
    private function someMethod()
    {
    }
}
