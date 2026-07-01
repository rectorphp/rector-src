<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Source\SomeTypedService;

final class AlsoClosure
{
    public function __construct(
        private SomeTypedService $someTypedService
    ) {
    }

    public function go()
    {
        $closure = function ($value) {
            $this->someTypedService->run($value);
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Source\SomeTypedService;

final class AlsoClosure
{
    public function __construct(
        private SomeTypedService $someTypedService
    ) {
    }

    public function go()
    {
        $closure = function (string $value) {
            $this->someTypedService->run($value);
        };
    }
}

?>
