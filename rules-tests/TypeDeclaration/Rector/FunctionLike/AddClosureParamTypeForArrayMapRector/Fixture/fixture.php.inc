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
     * @param array<int, Foo|Bar> $objects
     */
    public function runTwo(array $objects)
    {
        return array_map(function ($object) {
            return get_class($object);
        }, $objects);
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
     * @param array<int, Foo|Bar> $objects
     */
    public function runTwo(array $objects)
    {
        return array_map(function (\Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Source\Foo|\Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Source\Bar $object) {
            return get_class($object);
        }, $objects);
    }
}

?>
