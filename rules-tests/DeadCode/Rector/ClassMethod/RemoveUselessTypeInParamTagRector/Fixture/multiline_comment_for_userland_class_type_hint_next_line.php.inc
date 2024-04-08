<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Source\SomeConstantFloatType;

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
?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Source\SomeConstantFloatType;

final class SkipMultilineCommentForUserlandClassTypeHint
{
    /**
     * @param $primitiveValue
     *   A primitive is fine.
     * @param $userlandClass
     *   A user land class is not.
     */
    public function test(string $primitiveValue, callable $callableValue, SomeConstantFloatType $userlandClass)
    {
    }
}
?>
