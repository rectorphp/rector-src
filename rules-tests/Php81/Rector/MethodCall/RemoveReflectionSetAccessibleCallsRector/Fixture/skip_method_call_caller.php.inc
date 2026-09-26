<?php

namespace Rector\Tests\Php81\Rector\MethodCall\RemoveReflectionSetAccessibleCallsRector\Fixture;

use ReflectionProperty;

final class SkipMethodCallCaller
{
    public function run(): void
    {
        $this->getReflectionProperty()->setAccessible(true);
    }

    private function getReflectionProperty(): ReflectionProperty
    {
        return new ReflectionProperty('Foo', 'bar');
    }
}
