<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Source;

class ClassWithConstants
{
    public const NAME = 'SomeName';

    public const ORIGINAL_VALUE = 123;
}
