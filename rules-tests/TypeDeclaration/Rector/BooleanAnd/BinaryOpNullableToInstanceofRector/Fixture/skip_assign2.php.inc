<?php

namespace Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Source\SomeInstance;

final class SkipAssign2
{
    private ?SomeInstance $someClass;

    public function run(bool $a)
    {
        if ($a === true && ($this->someClass = $this->get()) && $this->someClass->someMethod()) {
            return $this->someClass->someMethod();
        }

        return 'no';
    }

    private function get(): ?SomeInstance
    {
        return rand(0, 1) ? new SomeInstance() : null;
    }
}
