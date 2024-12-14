<?php

namespace Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Fixture;

use Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeClass;
use Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum;

final class UsageOfGetKey
{
    public function run($value)
    {
        $name = SomeEnum::USED_TO_BE_CONST()->getKey();
        $enum = SomeEnum::USED_TO_BE_CONST();
        $name2 = $enum->getKey();
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Fixture;

use Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeClass;
use Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum;

final class UsageOfGetKey
{
    public function run($value)
    {
        $name = \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST->name;
        $enum = \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST;
        $name2 = $enum->name;
    }
}

?>
