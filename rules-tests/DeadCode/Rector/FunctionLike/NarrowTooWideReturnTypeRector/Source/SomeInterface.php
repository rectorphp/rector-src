<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Source;

interface SomeInterface
{
    public function getData(): string|int|bool;
}
