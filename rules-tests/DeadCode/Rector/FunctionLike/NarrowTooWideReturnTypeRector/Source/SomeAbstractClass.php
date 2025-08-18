<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Sourcet;

abstract class SomeAbstractClass
{
    abstract public function process(): string|int|array;
}
