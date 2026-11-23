<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Fixture;

class SkipArrayKeysAndValues
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
