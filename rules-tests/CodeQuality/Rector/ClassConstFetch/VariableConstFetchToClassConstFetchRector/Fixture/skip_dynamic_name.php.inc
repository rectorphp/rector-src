<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Fixture;

use Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Source\ClassWithConstants;

final class SkipDynamicName
{
    public function run(ClassWithConstants $classWithConstants, $dynamic)
    {
        return $classWithConstants::$dynamic;
    }
}
