<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Source;

final class ChildClassNotWithConstant extends ClassWithConstants
{
    public const ORIGINAL_VALUE = 456;
}
