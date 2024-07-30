<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

/**
 * @see https://3v4l.org/leYmD#v8.0.28
 */
final class SkipTypedDefaultNull
{
    public function run(string $a = null, $required)
    {
    }
}
