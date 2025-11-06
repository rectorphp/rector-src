<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Fixture;

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

    /**
     * @return class-string<\stdClass>|int
     */
    public function skipMixedByDoc(): string|int
    {
        /**
         * @var class-string<\stdClass> $class
         */
        $class = get();
        return $class;
    }
}

?>
