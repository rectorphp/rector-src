<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

final class SkipUsedByReflectionAsArrayCallable
{
    public function run(): int
    {
        $reflectionMethod = new \ReflectionMethod(...[$this, 'methodInjection']);

        return $reflectionMethod->getNumberOfParameters();
    }

    private function methodInjection(\stdClass $contextArgument): void
    {
    }
}
