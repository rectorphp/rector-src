<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Source\SomeTypedService;

final class StaticCallElsewhere
{
    public function go($value, $first)
    {
        SomeTypedService::funInt($first, $value);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Source\SomeTypedService;

final class StaticCallElsewhere
{
    public function go(int $value, $first)
    {
        SomeTypedService::funInt($first, $value);
    }
}

?>
