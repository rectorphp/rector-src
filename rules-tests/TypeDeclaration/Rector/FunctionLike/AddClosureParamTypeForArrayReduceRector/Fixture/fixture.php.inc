<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayReduceRector\Fixture;

class Fixture
{
    /**
     * @param list<string> $array
     */
    public function run(array $array)
    {
        return array_reduce($array, function ($carry, $value) {
            return $carry . $value;
        }, '');
    }

    /**
     * @param list<string|int> $array
     */
    public function runTwo(array $array)
    {
        return array_reduce($array, function ($carry, $value) {
            return $carry . $value;
        }, 100);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayReduceRector\Fixture;

class Fixture
{
    /**
     * @param list<string> $array
     */
    public function run(array $array)
    {
        return array_reduce($array, function (string $carry, string $value) {
            return $carry . $value;
        }, '');
    }

    /**
     * @param list<string|int> $array
     */
    public function runTwo(array $array)
    {
        return array_reduce($array, function (int|string $carry, string|int $value) {
            return $carry . $value;
        }, 100);
    }
}

?>
