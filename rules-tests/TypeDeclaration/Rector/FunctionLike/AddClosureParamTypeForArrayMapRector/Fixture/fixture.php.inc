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

    /**
     * @param array<int, string> $array
     * @param array<int, Foo|Bar> $arrayTwo
     */
    public function runThree(array $array, array $arrayTwo)
    {
        return array_map(function ($value, $key) {
            return get_class($value) . $key;
        }, $array, $arrayTwo);
    }

    /**
     * @param array<int, string> $array
     * @param array<string> $arrayTwo tested for the missing key
     */
    public function runFour(array $array, array $arrayTwo)
    {
        return array_map(function ($value, $key) {
            return get_class($value) . $key;
        }, $array, $arrayTwo);
    }

    /**
     * @param array<int, string> $array
     * @param list<string> $arrayTwo tested for the missing key
     */
    public function runFive(array $array, array $arrayTwo)
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

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Source\Bar;
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
        return array_map(function (\Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Source\Foo|string $value, int $key) {
            return get_class($value) . $key;
        }, $array, $arrayTwo);
    }

    /**
     * @param array<int, string> $array
     * @param array<int, Foo|Bar> $arrayTwo
     */
    public function runThree(array $array, array $arrayTwo)
    {
        return array_map(function (\Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Source\Bar|\Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Source\Foo|string $value, int $key) {
            return get_class($value) . $key;
        }, $array, $arrayTwo);
    }

    /**
     * @param array<int, string> $array
     * @param array<string> $arrayTwo tested for the missing key
     */
    public function runFour(array $array, array $arrayTwo)
    {
        return array_map(function (string $value, int|string $key) {
            return get_class($value) . $key;
        }, $array, $arrayTwo);
    }

    /**
     * @param array<int, string> $array
     * @param list<string> $arrayTwo tested for the missing key
     */
    public function runFive(array $array, array $arrayTwo)
    {
        return array_map(function (string $value, int $key) {
            return get_class($value) . $key;
        }, $array, $arrayTwo);
    }
}

?>
