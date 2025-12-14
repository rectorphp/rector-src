<?php

namespace Rector\Tests\Privatization\Rector\ClassConst\PrivatizeFinalClassConstantRector\Fixture;

use Rector\Tests\Privatization\Rector\ClassConst\PrivatizeFinalClassConstantRector\Source\AbstractClassWithProtectedClassConst;

final class ChangeParentWithoutCare extends AbstractClassWithProtectedClassConst
{
    protected const PROTECTED_LOCAL_CONST = 'local';
}
?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\ClassConst\PrivatizeFinalClassConstantRector\Fixture;

use Rector\Tests\Privatization\Rector\ClassConst\PrivatizeFinalClassConstantRector\Source\AbstractClassWithProtectedClassConst;

final class ChangeParentWithoutCare extends AbstractClassWithProtectedClassConst
{
    private const PROTECTED_LOCAL_CONST = 'local';
}
?>
