<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class ProtectedMethodInFinalClassImplementingInterface implements SomeUnknownInterface
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
