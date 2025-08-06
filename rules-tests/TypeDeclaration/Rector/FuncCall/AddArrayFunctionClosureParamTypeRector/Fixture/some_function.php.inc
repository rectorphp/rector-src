<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayFunctionClosureParamTypeRector\Fixture;

final class SomeFunction
{
    public function run()
    {
        $array = [1, 2, 3];
        $result = array_filter($array, fn ($item) => $item * 2);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayFunctionClosureParamTypeRector\Fixture;

final class SomeFunction
{
    public function run()
    {
        $array = [1, 2, 3];
        $result = array_filter($array, fn (int $item) => $item * 2);
    }
}

?>
