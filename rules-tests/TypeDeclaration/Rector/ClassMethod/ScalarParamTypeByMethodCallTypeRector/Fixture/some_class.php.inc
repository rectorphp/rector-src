<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Source\SomeTypedService;

final class UseDependency
{
    public function __construct(
        private SomeTypedService $someTypedService
    ) {
    }

    public function go($value)
    {
        $this->someTypedService->runInt($value);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Source\SomeTypedService;

final class UseDependency
{
    public function __construct(
        private SomeTypedService $someTypedService
    ) {
    }

    public function go(int $value)
    {
        $this->someTypedService->runInt($value);
    }
}

?>
