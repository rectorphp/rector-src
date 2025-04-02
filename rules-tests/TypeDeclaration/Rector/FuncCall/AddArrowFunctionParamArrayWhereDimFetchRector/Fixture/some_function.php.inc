<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrowFunctionParamArrayWhereDimFetchRector\Fixture;

final class FillParamArray
{
    public function run()
    {
        $array = [['name' => 'John']];

        $result = array_map(fn ($item) => $item['name'], $array);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrowFunctionParamArrayWhereDimFetchRector\Fixture;

final class FillParamArray
{
    public function run()
    {
        $array = [['name' => 'John']];

        $result = array_map(fn (array $item) => $item['name'], $array);
    }
}

?>
