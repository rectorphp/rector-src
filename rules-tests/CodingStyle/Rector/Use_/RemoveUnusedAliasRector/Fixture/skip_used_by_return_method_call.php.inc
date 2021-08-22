<?php

namespace Rector\Tests\CodingStyle\Rector\Use_\RemoveUnusedAliasRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Use_\RemoveUnusedAliasRector\Source\StandaloneClass;
use Rector\Tests\CodingStyle\Rector\Use_\RemoveUnusedAliasRector\Source\DifferentNamespace\StandaloneClass as StandaloneClassDifferentNamespace;

class SkipUsedByReturnMethodCall
{
    private function getObj()
    {
        return new StandaloneClassDifferentNamespace();
    }

    public function run()
    {
        return $this->getObj();
    }
}
