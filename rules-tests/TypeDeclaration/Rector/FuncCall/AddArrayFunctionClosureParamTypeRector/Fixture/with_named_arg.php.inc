<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayFunctionClosureParamTypeRector\Fixture;

final class WithNamedArg
{
    public function run()
    {
        $array = [1, 2, 3];
        $result = array_filter(callback: fn ($item) => $item * 2, array: $array);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayFunctionClosureParamTypeRector\Fixture;

final class WithNamedArg
{
    public function run()
    {
        $array = [1, 2, 3];
        $result = array_filter(callback: fn (int $item) => $item * 2, array: $array);
    }
}

?>
