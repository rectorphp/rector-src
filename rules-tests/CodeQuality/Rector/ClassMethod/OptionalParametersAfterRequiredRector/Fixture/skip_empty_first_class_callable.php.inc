<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class SkipEmptyFirstClassCallable
{
    public function run()
    {
        empty($this->call(...));
    }

    public function call()
    {
        return [];
    }
}
