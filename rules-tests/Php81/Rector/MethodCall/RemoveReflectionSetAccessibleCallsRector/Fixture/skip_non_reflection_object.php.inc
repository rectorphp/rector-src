<?php

namespace Rector\Tests\Php81\Rector\MethodCall\RemoveReflectionSetAccessibleCallsRector\Fixture;

final class SkipNonReflectionObject
{
    public function run(): void
    {
        $service = new \stdClass();
        $service->setAccessible(true);
    }
}
