<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Source;

abstract class SomeAbstractClass
{
    abstract public function process(): string|int|array;
}
