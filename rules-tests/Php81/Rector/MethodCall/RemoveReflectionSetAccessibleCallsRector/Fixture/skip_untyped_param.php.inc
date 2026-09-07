<?php

namespace Rector\Tests\Php81\Rector\MethodCall\RemoveReflectionSetAccessibleCallsRector\Fixture;

final class SkipUntypedParam
{
    public function run($reflectionProperty): void
    {
        $reflectionProperty->setAccessible(true);
    }
}
