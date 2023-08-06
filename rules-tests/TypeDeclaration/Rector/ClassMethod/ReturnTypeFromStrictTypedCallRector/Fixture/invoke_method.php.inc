<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Source\SomeExternalCaller;

final class InvokeMethod
{
    public function __invoke(SomeExternalCaller $someExternalCaller)
    {
        return $someExternalCaller->getName();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Source\SomeExternalCaller;

final class InvokeMethod
{
    public function __invoke(SomeExternalCaller $someExternalCaller): string
    {
        return $someExternalCaller->getName();
    }
}

?>
