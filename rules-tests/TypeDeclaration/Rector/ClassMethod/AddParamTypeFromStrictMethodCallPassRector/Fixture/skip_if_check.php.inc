<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromStrictMethodCallPassRector\Fixture;

final class SomeClass
{
    public function setValue($number)
    {
        if (is_string($number)) {
            return;
        }

        $this->checkInt($number);
    }

    private function checkInt(int $integer)
    {
    }
}
