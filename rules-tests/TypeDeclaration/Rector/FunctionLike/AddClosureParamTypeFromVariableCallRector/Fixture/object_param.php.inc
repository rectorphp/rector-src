<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromVariableCallRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromVariableCallRector\Source\Item;

final class ObjectParam
{
    public function run(): void
    {
        $printItem = function ($item) {
            echo $item->name;
        };

        $printItem(new Item());
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromVariableCallRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromVariableCallRector\Source\Item;

final class ObjectParam
{
    public function run(): void
    {
        $printItem = function (\Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromVariableCallRector\Source\Item $item) {
            echo $item->name;
        };

        $printItem(new Item());
    }
}

?>
