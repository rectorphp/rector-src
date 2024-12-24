<?php

namespace Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Source\CheckedClass;

class SkipAssign
{
    function run(?CheckedClass $someClass)
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
