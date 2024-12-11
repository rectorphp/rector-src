<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class SkipAssignRefFirstClassCallable
{
    public function run()
    {
        $a = &$this->call(...);
    }

    public function call()
    {
    }
}
