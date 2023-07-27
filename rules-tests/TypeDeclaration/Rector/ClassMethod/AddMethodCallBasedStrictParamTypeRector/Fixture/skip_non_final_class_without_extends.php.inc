<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

class SkipProtectedMethodInNonFinalClassWithoutBaseClass
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
