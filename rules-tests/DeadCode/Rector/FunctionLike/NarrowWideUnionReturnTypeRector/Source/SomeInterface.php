<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Source;

interface SomeInterface
{
    public function getData(): string|int|bool;
}
