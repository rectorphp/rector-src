<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromVariableCallRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromVariableCallRector\Source\Item;
use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromVariableCallRector\Source\Other;

final class SkipConflictingTypes
{
    public function run(): void
    {
        $printItem = function ($item) {
            var_dump($item);
        };

        $printItem(new Item());
        $printItem(new Other());
    }
}
