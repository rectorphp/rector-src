<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Fixture;

class ArrayKeysAndValues
{
    /**
     * @param array<string, string> $tokens
     */
    public function run(array $tokens): void
    {
        array_map(function ($token, $value) {
            echo $token . $value;
        }, array_keys($tokens), $tokens);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Fixture;

class ArrayKeysAndValues
{
    /**
     * @param array<string, string> $tokens
     */
    public function run(array $tokens): void
    {
        array_map(function (string $token, string $value) {
            echo $token . $value;
        }, array_keys($tokens), $tokens);
    }
}

?>
