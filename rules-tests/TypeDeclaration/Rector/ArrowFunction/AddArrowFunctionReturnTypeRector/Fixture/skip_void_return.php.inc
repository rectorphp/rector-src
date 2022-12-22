<?php

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

final class SkipVoidReturn
{
    public function doNohing(string $a): void
    {
        echo $a;
    }

    public function run()
    {
        fn (string $a) => $this->doNohing($a);
    }
}
