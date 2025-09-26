<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

class SkipUnionMixedArray
{
    public function run()
    {
        $this->exec($_SERVER['argv'] ?? []);
    }

    private function exec(array $argv)
    {
    }
}