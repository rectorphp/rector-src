<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Source;

class ClassWithSideEffect
{
    public const SOME_VALUE = 'value';

    public function __construct()
    {
        echo 'side effect ';
    }
}
