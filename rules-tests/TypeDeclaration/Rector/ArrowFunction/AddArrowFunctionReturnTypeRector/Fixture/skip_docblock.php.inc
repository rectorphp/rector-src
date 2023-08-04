<?php

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

class SkipDocblock
{
    public function run()
    {
        fn () => $this->execute();
    }

    /**
     * @return string
     */
    private function execute()
    {
        return 1;
    }
}
