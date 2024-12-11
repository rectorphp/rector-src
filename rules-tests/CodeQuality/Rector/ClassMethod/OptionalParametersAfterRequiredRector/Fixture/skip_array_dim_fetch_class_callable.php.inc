<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class SkipArrayDimFetchClassCallable
{
    public function run(array $a)
    {
        $a[$this->call(...)];
    }

    public function call()
    {
    }
}
