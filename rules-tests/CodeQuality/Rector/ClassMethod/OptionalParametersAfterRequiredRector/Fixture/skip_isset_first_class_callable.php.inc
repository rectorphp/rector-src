<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class SkipIssetFirstClassCallable
{
    public function run()
    {
        isset($this->call(...));
    }

    public function call()
    {
    }
}
