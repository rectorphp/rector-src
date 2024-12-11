<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

use stdClass;

final class SkipInstanceofFirstClassCallable
{
    public function run($a)
    {
        $this->call(...) instanceof stdClass;
    }

    public function call()
    {
    }
}
