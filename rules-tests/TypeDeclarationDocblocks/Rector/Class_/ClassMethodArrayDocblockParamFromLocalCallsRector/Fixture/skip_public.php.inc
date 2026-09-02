<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class SkipPublic
{
    public function go()
    {
        $this->run([2512, 3423]);

        $this->run([324, 534]);
    }

    public function run(array $items)
    {
    }
}
