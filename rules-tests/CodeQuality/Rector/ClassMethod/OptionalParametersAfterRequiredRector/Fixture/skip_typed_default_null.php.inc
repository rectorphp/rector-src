<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class SkipTypedDefaultNull
{
    public function run(string $a = null, $required)
    {
    }
}
