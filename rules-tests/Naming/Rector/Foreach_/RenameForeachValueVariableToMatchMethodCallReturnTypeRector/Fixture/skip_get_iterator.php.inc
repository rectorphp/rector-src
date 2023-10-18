<?php

namespace Rector\Tests\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchMethodCallReturnTypeRector\Fixture;

use Illuminate\Container\RewindableGenerator;

class SkipGetIterator
{
    public function run(RewindableGenerator $rewindableGenerator)
    {
        foreach ($rewindableGenerator->getIterator() as $someItem) {
            echo $someItem;
        }
    }
}
