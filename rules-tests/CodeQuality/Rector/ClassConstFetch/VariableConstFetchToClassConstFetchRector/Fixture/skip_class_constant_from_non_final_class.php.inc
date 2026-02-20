<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Fixture;

use Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Source\ClassWithConstants;

final class SkipClassConstantFromNonFinalClass
{
    public function run(ClassWithConstants $classWithConstants)
    {
        return $classWithConstants::NAME;
    }
}
