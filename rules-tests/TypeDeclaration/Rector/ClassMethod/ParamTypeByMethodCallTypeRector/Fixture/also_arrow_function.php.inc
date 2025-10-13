<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Source\SomeTypedService;

final class AlsoArrowFunction
{
    public function __construct(
        private SomeTypedService $someTypedService
    ) {
    }

    public function go()
    {
        $closure = fn ($value) => $this->someTypedService->run($value);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Source\SomeTypedService;

final class AlsoArrowFunction
{
    public function __construct(
        private SomeTypedService $someTypedService
    ) {
    }

    public function go()
    {
        $closure = fn (string $value) => $this->someTypedService->run($value);
    }
}

?>
