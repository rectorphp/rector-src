<?php

namespace Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Fixture;

use \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector;

final class FullyQualifiedClassName
{
    public function run($value)
    {
        $compare = \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST();
        $compare2 = MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST();
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Fixture;

use \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector;

final class FullyQualifiedClassName
{
    public function run($value)
    {
        $compare = \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST;
        $compare2 = \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST;
    }
}

?>
