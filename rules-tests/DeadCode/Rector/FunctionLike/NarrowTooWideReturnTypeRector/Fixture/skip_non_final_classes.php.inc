<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

class SkipNonFinalClass
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
