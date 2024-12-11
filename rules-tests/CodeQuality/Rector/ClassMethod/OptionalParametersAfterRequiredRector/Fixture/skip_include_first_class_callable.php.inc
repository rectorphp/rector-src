<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class SkipIncludeFirstClassCallable
{
    public function run()
    {
        include $this->call(...);
    }

    public function call()
    {
    }
}
