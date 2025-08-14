<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\RemoveUnusedReturnTypeRector\Fixture;

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
