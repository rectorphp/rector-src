<?php

namespace Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Fixture;

use Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum;

final class UsageOfGetKey
{
    public function run($value)
    {
        $name = SomeEnum::USED_TO_BE_CONST()->getKey();
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Fixture;

use Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum;

final class UsageOfGetKey
{
    public function run($value)
    {
        $name = \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST->name;
    }
}

?>
