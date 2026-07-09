<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Source\Bar;
use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Source\Foo;

class Fixture
{
    /**
     * @param array<int, string> $array
     */
    public function run(array $array)
    {
        return array_map(function ($value) {
            return $value;
        }, $array);
    }

    /**
     * @param array<int, string> $array
     * @param array<int, Foo> $arrayTwo
     */
    public function runTwo(array $array, array $arrayTwo)
    {
        return array_map(function ($value, $second) {
            return get_class($second) . $value;
        }, $array, $arrayTwo);
    }

    /**
     * @param array<int, string> $array
     * @param array<int, Foo|Bar> $arrayTwo
     */
    public function runThree(array $array, array $arrayTwo)
    {
        return array_map(function ($value, $second) {
            return get_class($second) . $value;
        }, $array, $arrayTwo);
    }

    /**
     * @param array<int, string> $array
     * @param array<string> $arrayTwo
     */
    public function runFour(array $array, array $arrayTwo)
    {
        return array_map(function ($value, $second) {
            return $second . $value;
        }, $array, $arrayTwo);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Source\Bar;
use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Source\Foo;

class Fixture
{
    /**
     * @param array<int, string> $array
     */
    public function run(array $array)
    {
        return array_map(function (string $value) {
            return $value;
        }, $array);
    }

    /**
     * @param array<int, string> $array
     * @param array<int, Foo> $arrayTwo
     */
    public function runTwo(array $array, array $arrayTwo)
    {
        return array_map(function (string $value, \Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Source\Foo $second) {
            return get_class($second) . $value;
        }, $array, $arrayTwo);
    }

    /**
     * @param array<int, string> $array
     * @param array<int, Foo|Bar> $arrayTwo
     */
    public function runThree(array $array, array $arrayTwo)
    {
        return array_map(function (string $value, \Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Source\Bar|\Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Source\Foo $second) {
            return get_class($second) . $value;
        }, $array, $arrayTwo);
    }

    /**
     * @param array<int, string> $array
     * @param array<string> $arrayTwo
     */
    public function runFour(array $array, array $arrayTwo)
    {
        return array_map(function (string $value, string $second) {
            return $second . $value;
        }, $array, $arrayTwo);
    }
}

?>
