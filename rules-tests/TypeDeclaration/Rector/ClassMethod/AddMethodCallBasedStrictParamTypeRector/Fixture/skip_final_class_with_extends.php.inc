<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class SkipProtectedMethodInFinalClassWithBaseClass extends SomeClass
{
    public function runFirst(string $data)
    {
        $this->process($data);
    }

    protected function process($data)
    {
    }
}

?>
