<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

final class SkipFunctionLikesWithoutParameterTypes
{
    /**
     * @param class-string<SomeInterface> $class
     * @return class-string<SomeInterface>|int
     */
    public function bar($class): string|int
    {
        return $class;
    }
}

?>
