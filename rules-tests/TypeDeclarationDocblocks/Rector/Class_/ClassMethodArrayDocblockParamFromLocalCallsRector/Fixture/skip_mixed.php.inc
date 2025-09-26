<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class SkipMixed
{
    public function go(array $mixed)
    {
        $this->run($mixed);
    }

    private function run(array $items)
    {
    }
}
