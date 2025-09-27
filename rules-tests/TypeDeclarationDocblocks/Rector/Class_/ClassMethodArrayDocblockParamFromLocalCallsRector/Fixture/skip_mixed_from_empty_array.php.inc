<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class SkipMixedFromEmptyArray
{
    public function execute(array $data)
    {
        $data = [];
        $this->run($data);
    }

    private function run(array $data)
    {
    }
}
