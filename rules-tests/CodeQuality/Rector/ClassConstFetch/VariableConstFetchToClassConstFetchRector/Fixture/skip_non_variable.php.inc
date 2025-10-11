<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Fixture;

use Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Source\ClassWithSideEffect;

final class SkipNonVariable
{
    function sideEffect(): ClassWithSideEffect
    {
        return new ClassWithSideEffect();
    }

    public function run()
    {
        echo $this->sideEffect()::SOME_VALUE;
    }
}
