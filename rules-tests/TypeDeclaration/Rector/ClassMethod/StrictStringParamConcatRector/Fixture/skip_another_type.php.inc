<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector\Fixture;

final class SkipAnotherType
{
    public function resolve($item)
    {
        return $item + 100;
    }
}
