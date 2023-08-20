<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Source\SomeConstantFloatType;

final class SkipMultilineCommentForUserlandClassTypeHint
{
    /**
     * @param string $primitiveValue
     *   A primitive is fine.
     * @param SomeConstantFloatType $userlandClass
     *   A user land class is not.
     */
    public function test(string $primitiveValue, callable $callableValue, SomeConstantFloatType $userlandClass)
    {
    }
}

