<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Source\SomeExternalCaller;

final class SkipExternalCallerReturnDoc
{
    public function getData(SomeExternalCaller $someExternalCaller, $x)
    {
        return $someExternalCaller->getData($x);
    }
}
