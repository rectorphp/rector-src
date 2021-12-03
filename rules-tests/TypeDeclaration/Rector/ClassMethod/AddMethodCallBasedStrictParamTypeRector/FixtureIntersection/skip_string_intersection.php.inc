<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\FixtureIntersection;

final class SkipStringIntersection
{
    public function execute()
    {
        $value = value() . __FILE__ ;
        $this->run($value);
    }

    private function run($value)
    {
    }
}
