<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromVariableCallRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromVariableCallRector\Source\Item;

final class SkipAlreadyTyped
{
    public function run(): void
    {
        $printItem = function (Item $item) {
            echo $item->name;
        };

        $printItem(new Item());
    }
}
