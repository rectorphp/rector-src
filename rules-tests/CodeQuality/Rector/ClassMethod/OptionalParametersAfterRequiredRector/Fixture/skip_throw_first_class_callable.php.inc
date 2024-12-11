<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

use InvalidArgumentException;

final class SkipThrowFirstClassCallable
{
    public function run()
    {
        throw $this->call(...);
    }

    public function call()
    {
        return new InvalidArgumentException();
    }
}
