<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Source\Foo;

class Fixture
{
    /**
     * @param array<int, string> $array
     */
    public function run(array $array)
    {
        return array_map(function ($value, $key) {
            return $value . $key;
        }, $array);
    }

    /**
     * @param array<int, string> $array
     * @param array<int, Foo> $arrayTwo
     */
    public function runTwo(array $array, array $arrayTwo)
    {
        return array_map(function ($value, $key) {
            return get_class($value) . $key;
        }, $array, $arrayTwo);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Source\Foo;

class Fixture
{
    /**
     * @param array<int, string> $array
     */
    public function run(array $array)
    {
        return array_map(function (string $value, int $key) {
            return $value . $key;
        }, $array);
    }

    /**
     * @param array<int, string> $array
     * @param array<int, Foo> $arrayTwo
     */
    public function runTwo(array $array, array $arrayTwo)
    {
        return array_map(function (string|\Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Source\Foo $value, int $key) {
            return get_class($value) . $key;
        }, $array, $arrayTwo);
    }
}

?>
