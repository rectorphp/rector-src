<?php

namespace Rector\Tests\CodingStyle\Rector\Use_\RemoveUnusedAliasRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Use_\RemoveUnusedAliasRector\Source\StandaloneClass;
use Rector\Tests\CodingStyle\Rector\Use_\RemoveUnusedAliasRector\Source\DifferentNamespace\StandaloneClass as StandaloneClassDifferentNamespace;

class SkipUsedByReturnMethodCall
{
    private function getObj()
    {
        if (rand(0, 1)) {
            return new StandaloneClassDifferentNamespace();
        }

        return null;
    }

    public function run()
    {
        $obj = $this->getObj();

        if (! $obj instanceof StandaloneClassDifferentNamespace) {
            return null;
        }

        return $obj;
    }
}
