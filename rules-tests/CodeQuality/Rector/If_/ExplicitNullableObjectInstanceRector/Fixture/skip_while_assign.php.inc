<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

use Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\CheckedClass;

class SkipWhileAssign
{
    public function run(?CheckedClass $someClass)
    {
        while ($someClass = $this->get()) {
            $someClass->run();
        }
    }

    private function get(): ?CheckedClass
    {
        return rand(0, 1) ? new CheckedClass() : null;
    }
}
