<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeDeclarationRector\Source;

class ClassForNamedParameters
{
    public function someCall(callable $callback, callable $anotherCallback): void
    {
    }
}
