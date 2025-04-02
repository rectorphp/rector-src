<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrowFunctionParamArrayWhereDimFetchRector\Fixture;

final class NestedDimFetches
{
    public function run()
    {
        $array = [['name' => 'John']];

        $result = array_map(fn ($item) => $item['name']['nested'], $array);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrowFunctionParamArrayWhereDimFetchRector\Fixture;

final class NestedDimFetches
{
    public function run()
    {
        $array = [['name' => 'John']];

        $result = array_map(fn (array $item) => $item['name']['nested'], $array);
    }
}

?>
