<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\FixtureTreatClassesAsFinal;

// both classes here on purpose as the abstract class is subject to test
// with child class override
abstract class SkipAbstractClass
{
    public function runFirst(string $data)
    {
        $this->process($data);
    }

    protected function process($data)
    {
    }
}

class SomeChildClass extends SkipAbstractClass
{
    public function runFirst(int|string $data)
    {
        $this->process($data);
    }
}
