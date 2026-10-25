<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

use Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance;

final class SkipAssign
{
    private ?SomeInstance $someClass;

    public function run()
    {
        if (($this->someClass = $this->get()) && $this->someClass->someMethod()) {
            return $this->someClass->someMethod();
        }

        return 'no';
    }

    private function get(): ?SomeInstance
    {
        return rand(0, 1) ? new SomeInstance() : null;
    }
}
