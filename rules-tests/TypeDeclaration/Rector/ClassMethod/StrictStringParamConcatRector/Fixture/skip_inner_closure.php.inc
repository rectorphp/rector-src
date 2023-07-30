<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector\Fixture;

final class SkipInnerClosure
{
    public function resolve($item)
    {
        $result = $item * 100;

        function ($item) {
            return $item . ' world';
        };

        return $result;
    }
}
