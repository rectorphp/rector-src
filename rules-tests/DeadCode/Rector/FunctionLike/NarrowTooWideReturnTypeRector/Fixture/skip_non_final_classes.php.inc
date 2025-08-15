<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

class NonFinalClass
{
    public function getData(): string|int|\DateTime
    {
        return 'hello';
    }

    protected function getInfo(): string|int|bool
    {
        return 'info';
    }
}

?>
