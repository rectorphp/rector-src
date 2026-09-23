<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Source\SomeTypedService;

final class SkipStringCallerParam
{
    public function __construct(
        private SomeTypedService $someTypedService
    ) {
    }

    public function go($value)
    {
        // string param accepts int/float/bool via coercion, so caller type stays open
        $this->someTypedService->run($value);
    }
}
