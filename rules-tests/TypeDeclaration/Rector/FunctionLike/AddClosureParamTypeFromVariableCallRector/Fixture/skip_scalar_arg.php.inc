<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromVariableCallRector\Fixture;

final class SkipScalarArg
{
    public function run(): void
    {
        $printValue = function ($value) {
            echo $value;
        };

        $printValue('hello');
    }
}
