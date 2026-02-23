<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Fixture;

use Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Source\ClassWithFinalConstant;

final class SkipDocblockFinalConstant
{
    public function run(ClassWithFinalConstant $classWithFinalConstant)
    {
        return $classWithFinalConstant::DOCBLOCK_FINAL;
    }
}
