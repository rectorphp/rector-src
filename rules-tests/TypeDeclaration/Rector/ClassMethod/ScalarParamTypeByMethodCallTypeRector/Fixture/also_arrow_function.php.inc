<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Source\SomeTypedService;

final class AlsoArrowFunction
{
    public function __construct(
        private SomeTypedService $someTypedService
    ) {
    }

    public function go()
    {
        $closure = fn ($value) => $this->someTypedService->runInt($value);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Source\SomeTypedService;

final class AlsoArrowFunction
{
    public function __construct(
        private SomeTypedService $someTypedService
    ) {
    }

    public function go()
    {
        $closure = fn (int $value) => $this->someTypedService->runInt($value);
    }
}

?>
