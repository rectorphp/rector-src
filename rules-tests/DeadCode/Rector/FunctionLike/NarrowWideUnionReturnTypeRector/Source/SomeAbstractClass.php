<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Sourcet;

abstract class SomeAbstractClass
{
    abstract public function process(): string|int|array;
}
